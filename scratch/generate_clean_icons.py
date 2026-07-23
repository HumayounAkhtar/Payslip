import os
import math
from PIL import Image, ImageDraw

statusbar_dir = r"c:\xampp\htdocs\Payslip\public\images\status-bar"
os.makedirs(statusbar_dir, exist_ok=True)

def save_scaled(img_large, target_size, filename):
    img_small = img_large.resize(target_size, Image.Resampling.LANCZOS)
    dest = os.path.join(statusbar_dir, filename)
    img_small.save(dest, "PNG")
    print(f"  {filename}: {img_small.width}x{img_small.height}px")

print("=== Generating Perfect iOS Status Bar Icons ===")

# ───────────────────────────────────────────────────────────────
# 1. SIGNAL BARS (Target size: 37 x 23 px)
# ───────────────────────────────────────────────────────────────
def generate_signal(active_bars, filename):
    W, H = 370, 230
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    bar_w = 38
    gap = 18
    bot = 205
    heights = [50, 95, 140, 185]
    
    # Center 4 bars in 370px width: total_w = 4*38 + 3*18 = 206
    left_start = (W - 206) // 2

    for i in range(4):
        x0 = left_start + i * (bar_w + gap)
        x1 = x0 + bar_w
        y0 = bot - heights[i]
        y1 = bot
        is_active = (i < active_bars)
        
        if is_active:
            color = (0, 0, 0, 255)
        else:
            color = (196, 196, 198, 255)

        d.rounded_rectangle([x0, y0, x1, y1], radius=10, fill=color)

    save_scaled(img, (37, 23), filename)

for n in range(1, 5):
    generate_signal(n, f"signal-{n}-bars.png")
generate_signal(4, "signal_original.png")

# ───────────────────────────────────────────────────────────────
# 2. WI-FI ICON (Target size: 33 x 23 px)
# ───────────────────────────────────────────────────────────────
def generate_wifi():
    W, H = 330, 230
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    cx, cy = W // 2, H - 35

    # Bottom dot
    dot_r = 16
    d.ellipse([cx - dot_r, cy - dot_r, cx + dot_r, cy + dot_r], fill=(0, 0, 0, 255))

    # 3 arcs (inner, middle, outer)
    radii = [55, 95, 135]
    stroke = 20
    a0, a1 = 220, 320

    for r in radii:
        bbox = [cx - r, cy - r, cx + r, cy + r]
        d.arc(bbox, start=a0, end=a1, fill=(0, 0, 0, 255), width=stroke)
        cr = stroke // 2 - 1
        for ang in [a0, a1]:
            rad = math.radians(ang)
            ex = cx + r * math.cos(rad)
            ey = cy + r * math.sin(rad)
            d.ellipse([ex - cr, ey - cr, ex + cr, ey + cr], fill=(0, 0, 0, 255))

    save_scaled(img, (33, 23), "wifi_original.png")

generate_wifi()

# ───────────────────────────────────────────────────────────────
# 3. BATTERY ICON (Target size: 49 x 23 px)
# ───────────────────────────────────────────────────────────────
def generate_battery(level, filename):
    W, H = 490, 230
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    # Battery Frame
    bL, bT, bR, bB = 20, 25, 415, 205
    outline_w = 16
    radius = 40
    d.rounded_rectangle([bL, bT, bR, bB], radius=radius, outline=(0, 0, 0, 255), width=outline_w)

    # Battery Nub
    nL, nT, nR, nB = 422, 80, 452, 150
    d.rounded_rectangle([nL, nT, nR, nB], radius=10, fill=(0, 0, 0, 255))

    # Inner Fill Block
    pad = outline_w + 12
    iL = bL + pad
    iT = bT + pad
    iR_max = bR - pad
    iB = bB - pad
    inner_r = max(4, radius - pad)

    if level == "full":
        iR = iR_max
        fill_color = (0, 0, 0, 255)
    elif level == "medium":
        iR = iL + int((iR_max - iL) * 0.50)
        fill_color = (0, 0, 0, 255)
    elif level == "low":
        iR = iL + int((iR_max - iL) * 0.25)
        fill_color = (0, 0, 0, 255)
    else:
        iR = iL + int((iR_max - iL) * 0.25)
        fill_color = (0, 0, 0, 255)

    if iR > iL:
        d.rounded_rectangle([iL, iT, iR, iB], radius=inner_r, fill=fill_color)

    save_scaled(img, (49, 23), filename)

for lv in ["full", "medium", "low"]:
    generate_battery(lv, f"battery-{lv}.png")

print("iOS Status bar icons updated!")
