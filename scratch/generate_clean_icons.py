import os
import math
from PIL import Image, ImageDraw

statusbar_dir = r"c:\xampp\htdocs\Payslip\public\images\status-bar"
os.makedirs(statusbar_dir, exist_ok=True)

# Helper to scale down a high-res image using LANCZOS
def save_scaled_image(img_large, filename):
    w, h = img_large.size
    img_small = img_large.resize((w // 10, h // 10), Image.Resampling.LANCZOS)
    dest_path = os.path.join(statusbar_dir, filename)
    img_small.save(dest_path, "PNG")
    print(f"Generated {filename}")

# 1. Cellular Signal Icons (24x30)
def generate_signal_icon(active_bars, filename):
    # Canvas size 240x300
    img = Image.new("RGBA", (240, 300), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    # 4 bars configuration
    # x ranges: [30, 60], [80, 110], [130, 160], [180, 210]
    # y-bottom is 240 (which means y-bottom in 1x is 24)
    # y-tops: 180, 140, 100, 60 (heights: 60, 100, 140, 180)
    bar_coords = [
        (30, 180, 60, 240),
        (80, 140, 110, 240),
        (130, 100, 160, 240),
        (180, 60, 210, 240)
    ]
    
    for i, coords in enumerate(bar_coords):
        is_active = i < active_bars
        color = (0, 0, 0, 255) if is_active else (0, 0, 0, 60)
        draw.rounded_rectangle(coords, radius=10, fill=color)
        
    save_scaled_image(img, filename)

# Generate signal icons
for bars in range(1, 5):
    generate_signal_icon(bars, f"signal-{bars}-bars.png")
# Copy signal-4-bars to signal_original as well
generate_signal_icon(4, "signal_original.png")

# 2. Wi-Fi Icon (28x30)
def generate_wifi_icon():
    # Canvas size 280x300
    img = Image.new("RGBA", (280, 300), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    center_x, center_y = 140, 230
    
    # Draw bottom dot: radius 15
    draw.ellipse([center_x - 15, center_y - 15, center_x + 15, center_y + 15], fill=(0, 0, 0, 255))
    
    # Draw 3 concentric arcs
    # Arc 1: R=55, Arc 2: R=105, Arc 3: R=155
    # Width = 20, spacing gap = 30
    radii = [55, 105, 155]
    start_angle = 220
    end_angle = 320
    
    for r in radii:
        bbox = [center_x - r, center_y - r, center_x + r, center_y + r]
        # draw.arc in PIL: angles are 0 to 360
        draw.arc(bbox, start=start_angle, end=end_angle, fill=(0, 0, 0, 255), width=20)
        
        # Draw rounded end caps for each arc
        for angle in [start_angle, end_angle]:
            rad = math.radians(angle)
            cx = center_x + r * math.cos(rad)
            cy = center_y + r * math.sin(rad)
            # circle of radius 10 (half of width 20)
            draw.ellipse([cx - 10, cy - 10, cx + 10, cy + 10], fill=(0, 0, 0, 255))
            
    save_scaled_image(img, "wifi_original.png")

generate_wifi_icon()

# 3. Battery Icons (50x30)
def generate_battery_icon(level, filename):
    # Canvas size 500x300
    img = Image.new("RGBA", (500, 300), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)
    
    # Battery body outline: x=[80, 380], y=[80, 220], radius=35, width=20
    draw.rounded_rectangle([80, 80, 380, 220], radius=35, outline=(0, 0, 0, 255), width=20)
    
    # Battery tip: x=[390, 410], y=[120, 180], radius=10
    draw.rounded_rectangle([390, 120, 410, 180], radius=10, fill=(0, 0, 0, 255))
    
    # Battery inner fill: x=[120, fill_right], y=[120, 180]
    # Max fill_right is 340 (max fill width is 220)
    if level == "full":
        fill_right = 340
        fill_color = (0, 0, 0, 255)
    elif level == "medium":
        fill_right = 230 # 50% of 220 is 110, so 120+110=230
        fill_color = (0, 0, 0, 255)
    elif level == "low":
        fill_right = 153 # 15% of 220 is 33, so 120+33=153
        fill_color = (239, 68, 68, 255) # Red color
    else:
        fill_right = 120
        fill_color = (0, 0, 0, 255)
        
    if fill_right > 120:
        draw.rounded_rectangle([120, 120, fill_right, 180], radius=15, fill=fill_color)
        
    save_scaled_image(img, filename)

for level in ["full", "medium", "low"]:
    generate_battery_icon(level, f"battery-{level}.png")

print("All status bar icons generated successfully!")
