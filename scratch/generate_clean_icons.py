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

print("=== Generating Authentic iOS Status Bar Icons ===")

# ───────────────────────────────────────────────────────────────
# 1. SIGNAL BARS (Target size: 38 x 24 px)
#    4 rounded pill bars, 10x canvas size (380 x 240)
# ───────────────────────────────────────────────────────────────
def generate_signal(active_bars, filename):
    W, H = 380, 240
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    bar_w = 42
    gap = 20
    bot = 210

    # heights for bars 1, 2, 3, 4
    heights = [65, 105, 145, 185]
    # Center 4 bars in 380px width: total_w = 4*42 + 3*20 = 228
    left_start = (W - 228) // 2

    for i in range(4):
        x0 = left_start + i * (bar_w + gap)
        x1 = x0 + bar_w
        y0 = bot - heights[i]
        y1 = bot
        is_active = (i < active_bars)
        
        if is_active:
            color = (0, 0, 0, 255)
        else:
            color = (196, 196, 198, 255) # Light gray for inactive bars (iOS style)

        d.rounded_rectangle([x0, y0, x1, y1], radius=12, fill=color)

    save_scaled(img, (38, 24), filename)

for n in range(1, 5):
    generate_signal(n, f"signal-{n}-bars.png")
generate_signal(4, "signal_original.png")

# ───────────────────────────────────────────────────────────────
# 2. WI-FI ICON (Target size: 34 x 22 px)
#    Concentric arcs + dot, 10x canvas size (340 x 220)
# ───────────────────────────────────────────────────────────────
def generate_wifi():
    W, H = 340, 220
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    cx, cy = W // 2, H - 28

    # Bolder Bottom dot
    dot_r = 24
    d.ellipse([cx - dot_r, cy - dot_r, cx + dot_r, cy + dot_r], fill=(0, 0, 0, 255))

    # 3 arcs (inner, middle, outer) with thick bold stroke (36px)
    radii = [55, 95, 135]
    stroke = 36
    a0, a1 = 215, 325

    for r in radii:
        bbox = [cx - r, cy - r, cx + r, cy + r]
        d.arc(bbox, start=a0, end=a1, fill=(0, 0, 0, 255), width=stroke)
        cr = stroke // 2 - 1
        for ang in [a0, a1]:
            rad = math.radians(ang)
            ex = cx + r * math.cos(rad)
            ey = cy + r * math.sin(rad)
            d.ellipse([ex - cr, ey - cr, ex + cr, ey + cr], fill=(0, 0, 0, 255))

    save_scaled(img, (34, 22), "wifi_original.png")

generate_wifi()

# ───────────────────────────────────────────────────────────────
# 3. BATTERY ICON (Target size: 48 x 24 px)
#    iOS outline battery frame + interior block fill, 10x canvas (480 x 240)
# ───────────────────────────────────────────────────────────────
def generate_battery(level, filename):
    W, H = 480, 240
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    # Battery Outer Frame
    bL, bT, bR, bB = 25, 25, 405, 215
    outline_w = 20
    radius = 45
    d.rounded_rectangle([bL, bT, bR, bB], radius=radius, outline=(0, 0, 0, 255), width=outline_w)

    # Battery Right Nub
    nL, nT, nR, nB = 412, 85, 442, 155
    d.rounded_rectangle([nL, nT, nR, nB], radius=12, fill=(0, 0, 0, 255))

    # Battery Interior Fill Block
    pad = outline_w + 12 # 32px padding inside outline frame
    iL = bL + pad
    iT = bT + pad
    iR_max = bR - pad
    iB = bB - pad
    inner_r = max(6, radius - pad)

    if level == "full":
        iR = iR_max
        fill_color = (0, 0, 0, 255)
    elif level == "medium":
        iR = iL + int((iR_max - iL) * 0.50)
        fill_color = (0, 0, 0, 255)
    elif level == "low":
        iR = iL + int((iR_max - iL) * 0.25)
        fill_color = (0, 0, 0, 255) # Match low charge black block from reference
    else:
        iR = iL + int((iR_max - iL) * 0.25)
        fill_color = (0, 0, 0, 255)

    if iR > iL:
        d.rounded_rectangle([iL, iT, iR, iB], radius=inner_r, fill=fill_color)

    save_scaled(img, (48, 24), filename)

for lv in ["full", "medium", "low"]:
    generate_battery(lv, f"battery-{lv}.png")

print("iOS Status bar icons updated successfully!")
