from uielement import UIElement

class UILabel(UIElement):
    def __init__(self, text, position, width, height):
        super().__init__(position, width, height)
        self.text = text
        self.background = (0, 0, 0, 160) 

        self.foreground = (255, 255, 255)