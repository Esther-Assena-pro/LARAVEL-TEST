# Graphic Part
import sys
import os
#Ajouter dynamiquement le chemin parent (view) à sys.path
sys.path.append(os.path.dirname(os.path.abspath(__file__)) + "/..")


from tableau.quadrans import Quadrant
from tableau.echequier import draw_chessboard_with_quadrants, quadrants
from tableau.echequier import setup_board_with_background
from turtle import *
from tableau.quadrans import Quadrant
from tableau.echequier import draw_chessboard_with_quadrants
from tableau.echequier import quadrants




def main():

    setup_board_with_background("C:/Users/esthe/OneDrive/Desktop/katarenga proj/view/resources/images/plateauVide.png")
    
    draw_chessboard_with_quadrants(quadrants)

   
    quadrants[0].swap_orientation()
    quadrants[1].swap_orientation()

    
    draw_chessboard_with_quadrants(quadrants)
    done()

if __name__ == "__main__":
    main()