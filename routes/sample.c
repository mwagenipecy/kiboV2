using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;

namespace NameConsistencyDemo
{
    internal class Program
    {
        static void Main(string[] args)
        {
            var names = new[]
            {
                "Neema Mwitte John",
                "Neema M. John",
                "N. Mwitte John",
                "John Neema Mwitte",
                "Neema Mwitte J."
            };

            bool isOneSubject = IsOneSubject(names);
            Console.WriteLine($"Is one subject: {isOneSubject}");

            var (score, rep, outliers) =
                NameConsistencyScorer.ScoreWithExplanation(names, treatAsCompany: false);

            Console.WriteLine($"Score = {score:0.000}");
            Console.WriteLine($"Representative = {rep}");

            if (outliers.Count > 0)
            {
                Console.WriteLine("\nOutliers:");
                foreach (var o in outliers)
                    Console.WriteLine($"  {o.name}, maxSimToCluster = {o.maxSimToCluster:0.000}");
            }
            else
            {
                Console.WriteLine("\nNo outliers detected.");
            }

            Console.ReadLine();
        }

        static bool IsOneSubject(
            string[] names,
            bool treatAsCompany = false,
            double threshold = 0.75)
        {
            var (score, _, _) =
                NameConsistencyScorer.ScoreWithExplanation(names, treatAsCompany);

            return score >= threshold;
        }
    }

    public static class NameConsistencyScorer
    {
        public static (double score, string representative, List<(string name, double maxSimToCluster)> outliers)
            ScoreWithExplanation(
                IReadOnlyList<string> names,
                bool treatAsCompany = false,
                double linkThreshold = 0.70,
                double alphaCoverage = 3.0,
                double betaOutlier = 2.0)
        {
            // Handle edge cases
            if (names == null || names.Count == 0)
                return (0.0, "", new List<(string, double)>());

            if (names.Count == 1)
                return (1.0, names[0], new List<(string, double)>());

            // Normalize names once
            var items = names
                .Select(s => new NameItem { Raw = s, Norm = Normalize(s) })
                .ToList();

            int n = items.Count;

            // Calculate similarity matrix once
            var sims = new double[n, n];
            for (int i = 0; i < n; i++)
            {
                sims[i, i] = 1.0;
                for (int j = i + 1; j < n; j++)
                {
                    double s = NameSimilarity(items[i].Norm, items[j].Norm);
                    sims[i, j] = sims[j, i] = s;
                }
            }

            // Find connected components
            var components = ConnectedComponents(sims, linkThreshold);
            var main = components.OrderByDescending(c => c.Count).First();

            // Calculate coverage penalty
            double coverage = (double)main.Count / n;
            double pCov = Math.Pow(coverage, alphaCoverage);

            // Calculate base similarity within main cluster
            var within = new List<double>();
            var mainList = main.ToList();
            for (int a = 0; a < mainList.Count; a++)
                for (int b = a + 1; b < mainList.Count; b++)
                    within.Add(sims[mainList[a], mainList[b]]);

            double baseSim = within.Count == 0 ? 1.0 : Median(within);

            // Calculate outlier penalty
            double sumDistinctiveness = 0.0;
            foreach (var o in Enumerable.Range(0, n).Where(i => !main.Contains(i)))
            {
                double maxToCluster = main.Max(c => sims[o, c]);
                sumDistinctiveness += Math.Max(0, 0.8 - maxToCluster);
            }

            double pOut = Math.Exp(-betaOutlier * sumDistinctiveness);
            double score = Clamp01(baseSim * pCov * pOut);

            // Find representative (name with highest average similarity to others in cluster)
            int repIdx = -1;
            double maxAvg = -1.0;

            foreach (var i in main)
            {
                double sum = 0;
                int count = 0;
                foreach (var j in main)
                {
                    if (i != j)
                    {
                        sum += sims[i, j];
                        count++;
                    }
                }
                double avg = count > 0 ? sum / count : 1.0;
                if (avg > maxAvg)
                {
                    maxAvg = avg;
                    repIdx = i;
                }
            }

            // Find outliers
            var outliers = new List<(string, double)>();
            foreach (var o in Enumerable.Range(0, n).Where(i => !main.Contains(i)))
            {
                double maxToCluster = main.Max(c => sims[o, c]);
                outliers.Add((items[o].Raw, maxToCluster));
            }

            return (score, items[repIdx].Raw, outliers);
        }

        public static double ScoreSameSubject(
            IReadOnlyList<string> names,
            bool treatAsCompany = false,
            double linkThreshold = 0.70,
            double alphaCoverage = 3.0,
            double betaOutlier = 2.0)
        {
            var (score, _, _) = ScoreWithExplanation(names, treatAsCompany, linkThreshold, alphaCoverage, betaOutlier);
            return score;
        }

        static double NameSimilarity(string a, string b)
        {
            var ta = a.Split(' ', StringSplitOptions.RemoveEmptyEntries);
            var tb = b.Split(' ', StringSplitOptions.RemoveEmptyEntries);

            // Special handling for two-token names with matching last names and initial
            if (ta.Length == 2 && tb.Length == 2)
            {
                string aFirst = ta[0], aLast = ta[1];
                string bFirst = tb[0], bLast = tb[1];

                if (aLast == bLast)
                {
                    if ((aFirst.Length == 1 && bFirst.StartsWith(aFirst)) ||
                        (bFirst.Length == 1 && aFirst.StartsWith(bFirst)))
                    {
                        return 0.95;
                    }
                }
            }

            return JaroWinkler(a, b);
        }

        static string Normalize(string s)
        {
            if (string.IsNullOrWhiteSpace(s)) return "";

            s = s.ToLowerInvariant();
            s = RemoveDiacritics(s);

            var sb = new StringBuilder();
            foreach (char c in s)
                sb.Append(char.IsLetter(c) ? c : ' ');

            // Split, remove empties, sort alphabetically (order-independent matching)
            return string.Join(" ",
                sb.ToString()
                  .Split(' ', StringSplitOptions.RemoveEmptyEntries)
                  .OrderBy(t => t));
        }

        static List<HashSet<int>> ConnectedComponents(double[,] sims, double threshold)
        {
            int n = sims.GetLength(0);
            var visited = new bool[n];
            var comps = new List<HashSet<int>>();

            for (int i = 0; i < n; i++)
            {
                if (visited[i]) continue;

                var set = new HashSet<int>();
                var q = new Queue<int>();
                q.Enqueue(i);
                visited[i] = true;

                while (q.Count > 0)
                {
                    int u = q.Dequeue();
                    set.Add(u);

                    for (int v = 0; v < n; v++)
                        if (!visited[v] && sims[u, v] >= threshold)
                        {
                            visited[v] = true;
                            q.Enqueue(v);
                        }
                }

                comps.Add(set);
            }

            return comps;
        }

        static double JaroWinkler(string s1, string s2)
        {
            if (s1 == s2) return 1.0;
            if (s1.Length == 0 || s2.Length == 0) return 0.0;

            int len1 = s1.Length, len2 = s2.Length;
            int matchDist = Math.Max(len1, len2) / 2 - 1;
            if (matchDist < 0) matchDist = 0;

            var s1m = new bool[len1];
            var s2m = new bool[len2];

            int matches = 0;
            for (int i = 0; i < len1; i++)
            {
                int start = Math.Max(0, i - matchDist);
                int end = Math.Min(i + matchDist + 1, len2);

                for (int j = start; j < end; j++)
                {
                    if (s2m[j]) continue;
                    if (s1[i] != s2[j]) continue;
                    s1m[i] = s2m[j] = true;
                    matches++;
                    break;
                }
            }

            if (matches == 0) return 0.0;

            // Count transpositions
            int k = 0, trans = 0;
            for (int i = 0; i < len1; i++)
            {
                if (!s1m[i]) continue;
                while (!s2m[k]) k++;
                if (s1[i] != s2[k]) trans++;
                k++;
            }

            double m = matches;
            double jaro = (m / len1 + m / len2 + (m - trans / 2.0) / m) / 3.0;

            // Winkler modification: boost score for common prefix
            int prefix = 0;
            for (int i = 0; i < Math.Min(4, Math.Min(len1, len2)); i++)
            {
                if (s1[i] == s2[i]) prefix++;
                else break;
            }

            return Clamp01(jaro + 0.1 * prefix * (1.0 - jaro));
        }

        static string RemoveDiacritics(string text)
        {
            var norm = text.Normalize(NormalizationForm.FormD);
            var sb = new StringBuilder();

            foreach (char c in norm)
                if (CharUnicodeInfo.GetUnicodeCategory(c) != UnicodeCategory.NonSpacingMark)
                    sb.Append(c);

            return sb.ToString().Normalize(NormalizationForm.FormC);
        }

        static double Median(List<double> xs)
        {
            if (xs.Count == 0) return 0;
            xs.Sort();
            int n = xs.Count;
            if (n % 2 == 1) return xs[n / 2];
            return (xs[n / 2 - 1] + xs[n / 2]) / 2.0;
        }

        static double Clamp01(double x)
            => x < 0 ? 0 : (x > 1 ? 1 : x);

        sealed class NameItem
        {
            public string Raw { get; set; }
            public string Norm { get; set; }
        }
    }
}


