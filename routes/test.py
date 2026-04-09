import sys
import os
from PIL import Image

def make_watermark(src: str, dst: str, opacity: float = 0.07):
    img = Image.open(src).convert("RGBA")
    r, g, b, a = img.split()
    a = a.point(lambda px: int(px * opacity))
    result = Image.merge("RGBA", (r, g, b, a))
    result.save(dst, "PNG")
    print(f"Saved {dst}  (opacity={opacity*100:.0f}%)")

if __name__ == "__main__":
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))

    src = sys.argv[1] if len(sys.argv) > 1 else os.path.join(BASE_DIR, "logo.png")
    dst = sys.argv[2] if len(sys.argv) > 2 else os.path.join(BASE_DIR, "logo_wm.png")

    make_watermark(src, dst, opacity=0.07)