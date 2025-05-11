import pygame
from uielement import UIElement

class UIPanel(UIElement):
    def __init__(self, position, width, height):
        super().__init__(position, width, height)
        self.background = pygame.Color(180, 180, 180)
        self.border_color = pygame.Color("black")
        self.border_width = 1

    def draw(self, surface):
        pygame.draw.rect(surface, self.background, (self.position[0], self.position[1], self.width, self.height))
        if self.border_width > 0:
            pygame.draw.rect(surface, self.border_color, (self.position[0], self.position[1], self.width, self.height), self.border_width)