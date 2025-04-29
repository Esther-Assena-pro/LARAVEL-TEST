#   it Handles the overall chessboard
from turtle import *
from tableau.quadrans import Quadrant


recto_1 = [
    ["blue", "red", "green", "yellow"],
    ["yellow", "green", "red", "blue"],
    ["blue", "red", "green", "yellow"],
    ["yellow", "green", "red", "blue"]
]

verso_1 = [
    ["red", "blue", "yellow", "green"],
    ["green", "yellow", "blue", "red"],
    ["red", "blue", "yellow", "green"],
    ["green", "yellow", "blue", "red"]
]


# Initialiser les quadrants
quadrants = [
    Quadrant(0, recto_1, verso_1),
    Quadrant(1, recto_1, verso_1),
    Quadrant(2, recto_1, verso_1),
    Quadrant(3, recto_1, verso_1)
]
# desiiner premierement ma fenetre
title("Katarenga")
setup(800, 600)


# plateau vide
def setup_board_with_background(image_path):
    import os
    screen = Screen()
    screen.setup(width=800, height=800)  

    if os.path.exists(image_path):
        screen.bgpic(image_path) 
    else:
        print(f"Error: Background image '{image_path}' not found.")
        screen.bgcolor("white")

    screen.title("Katarenga - Plateau de Jeu")
    screen.tracer(0)    
    screen.update() 
   




# les carreaux de l'échiquier
def draw_square(x, y, size, color):
    up()
    speed("fastest")
    goto(x, y)
    down()
    fillcolor(color)
    begin_fill()
    for _ in range(4):
        forward(size)
        right(90)
    end_fill()


# dessine d'abord notre preimer quadrant
def draw_quadrant(x, y, square_size, face):
    # Dessiner le contour du carré
    for row in range(4):
        for col in range(4):
            color = face[row][col]
            draw_square(x + col * square_size, y - row * square_size, square_size, color)

def draw_chessboard_with_quadrants(quadrants):
    square_size = 192
    start_x = -384
    start_y = 384
    for row in range(4):
        for col in range (4):
            # Dessiner le carré de l'échiquier
            color = quadrants[row % len(quadrants)].current_face[row][col]
            draw_square(start_x + col * square_size, start_y - row * square_size, square_size, color)   
            

# Dessiner chaque quadrant
    for quadrant in quadrants:
        quadrant_x = start_x + (quadrant.id % 2) * 4 * square_size
        quadrant_y = start_y - (quadrant.id // 2) * 4 * square_size
        draw_quadrant(quadrant_x, quadrant_y, square_size, quadrant.get_current_face())

