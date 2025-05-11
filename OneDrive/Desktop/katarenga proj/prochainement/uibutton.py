import pygame
from uielement import UIElement
from prochainement.soundeffect import SoundEffect

class UIButton(UIElement):
    def __init__(self, text, position, width, height):
        super().__init__(position, width, height)
        self.text = text
        self.background = pygame.Color(0, 19, 70)
        self.border_width = 1
        self.border_color = pygame.Color("black")

    def on_mouse_down(self, x, y):
        self.background = pygame.Color(104, 147, 255)
        self.invalidate()
        SoundEffect.play_click_sound()

    def on_mouse_up(self, x, y):
        self.background = pygame.Color(0, 19, 70)
        self.invalidate()
        super().on_mouse_up(x, y)

    def on_mouse_enter(self):
        self.background = pygame.Color(0, 19, 255)
        self.invalidate()

    def on_mouse_leave(self):
        self.background = pygame.Color(104, 19, 255)
        self.invalidate()