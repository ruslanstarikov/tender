import os
import json
import svgwrite
from typing import List, Dict

# Define output directories
svg_dir = "/mnt/data/window_svgs"
os.makedirs(svg_dir, exist_ok=True)

# Define a basic window config list
window_configs = [
    {"filename": "fixed-1", "type": "fixed", "panels": 1, "config": []},
    {"filename": "fixed-2-horizontal", "type": "fixed", "panels": 2, "config": []},
    {"filename": "casement-left", "type": "casement", "panels": 1, "config": ["left-hinged"]},
    {"filename": "casement-right", "type": "casement", "panels": 1, "config": ["right-hinged"]},
    {"filename": "casement-lr", "type": "casement", "panels": 2, "config": ["left-hinged", "right-hinged"]},
    {"filename": "awning-1", "type": "awning", "panels": 1, "config": ["top-hinged"]},
    {"filename": "hopper-1", "type": "hopper", "panels": 1, "config": ["bottom-hinged"]},
    {"filename": "sliding-ox", "type": "sliding", "panels": 2, "config": ["fixed", "slides-left"]},
    {"filename": "sliding-xo", "type": "sliding", "panels": 2, "config": ["slides-right", "fixed"]},
    {"filename": "tilt-turn-left", "type": "tilt-turn", "panels": 1, "config": ["tilt", "turn-left"]},
    {"filename": "doublehung-double", "type": "doublehung", "panels": 1, "config": ["top-slides", "bottom-slides"]},
]

# Function to draw a simple dashed diagonal line (opening direction)
def draw_window_svg(filename: str, config: Dict):
    dwg = svgwrite.Drawing(os.path.join(svg_dir, f"{filename}.svg"), size=("100px", "100px"))
    panel_count = config["panels"]
    panel_width = 100 / panel_count

    for i in range(panel_count):
        x_offset = i * panel_width
        dwg.add(dwg.rect(insert=(x_offset, 0), size=(panel_width, 100), fill="none", stroke="black"))

        if "left-hinged" in config["config"] and i == 0:
            dwg.add(dwg.line(start=(x_offset, 0), end=(x_offset + panel_width, 100),
                             stroke="black", stroke_dasharray="5,5"))
        if "right-hinged" in config["config"] and i == panel_count - 1:
            dwg.add(dwg.line(start=(x_offset + panel_width, 0), end=(x_offset, 100),
                             stroke="black", stroke_dasharray="5,5"))
        if "top-hinged" in config["config"] and i == 0:
            dwg.add(dwg.line(start=(x_offset, 0), end=(x_offset + panel_width, 100),
                             stroke="black", stroke_dasharray="2,2"))
        if "bottom-hinged" in config["config"] and i == 0:
            dwg.add(dwg.line(start=(x_offset, 100), end=(x_offset + panel_width, 0),
                             stroke="black", stroke_dasharray="2,2"))
        if "tilt" in config["config"] and i == 0:
            dwg.add(dwg.line(start=(x_offset + 10, 0), end=(x_offset + panel_width - 10, 90),
                             stroke="black", stroke_dasharray="3,3"))
        if "turn-left" in config["config"] and i == 0:
            dwg.add(dwg.line(start=(x_offset, 0), end=(x_offset + panel_width, 100),
                             stroke="black", stroke_dasharray="3,3"))
        if "top-slides" in config["config"]:
            dwg.add(dwg.line(start=(x_offset + 20, 10), end=(x_offset + 20, 40),
                             stroke="black", stroke_dasharray="4,2"))
        if "bottom-slides" in config["config"]:
            dwg.add(dwg.line(start=(x_offset + 20, 60), end=(x_offset + 20, 90),
                             stroke="black", stroke_dasharray="4,2"))
        if "slides-left" in config["config"] and i == 1:
            dwg.add(dwg.line(start=(x_offset + panel_width - 10, 50), end=(x_offset, 50),
                             stroke="black", stroke_dasharray="4,2"))
        if "slides-right" in config["config"] and i == 0:
            dwg.add(dwg.line(start=(x_offset + 10, 50), end=(x_offset + panel_width, 50),
                             stroke="black", stroke_dasharray="4,2"))

    dwg.save()

# Generate SVGs
for config in window_configs:
    draw_window_svg(config["filename"], config)

# Generate JSON config
json_path = os.path.join(svg_dir, "window_configs.json")
with open(json_path, "w") as f:
    json.dump(window_configs, f, indent=2)

svg_dir
