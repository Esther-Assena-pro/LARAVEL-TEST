import pygame
from gameengine import GameEngine

if __name__ == "__main__":
    pygame.init()
    GameEngine.initialize()
    GameEngine.start()