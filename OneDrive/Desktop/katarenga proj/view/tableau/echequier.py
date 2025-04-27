from turtle import *
from turtle import *

# desiiner premierement ma fenetre
title("Katarenga")
setup(800, 600)

# les carreaux de l'échiquier
#   dessiner un carré
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

#   dessiner notre plateau d'échecs
def draw_chessboard():
    square_size = 50
    start_x = -200
    start_y = 200
    colors = ["#866121", "#FFFFFF"]
    highlight_color = "red" 

    plateau_size = square_size * 8 + 40
    draw_square(start_x - 20, start_y + 20, plateau_size, "#8B4513") 


    for row in range(8):
        for col in range(8):
            x = start_x + col * square_size
            y = start_y - row * square_size
            color = colors[(row + col) % 2]
            draw_square(x, y, square_size, color)
            if row == 0 or row == 7 or col == 0 or col == 7:
                color = highlight_color 
            else:
                color = colors[( row + col ) % 2]

# Dessiner les cases de l'échiquier
draw_chessboard()
done()




