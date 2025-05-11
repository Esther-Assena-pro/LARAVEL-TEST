import pygame
from uielement import UIElement
from prochainement.uipanel import UIPanel
from prochainement.uilabel import UILabel
from uibutton import UIButton

class UIDialog(UIElement):
    def __init__(self, message, position, width, height):
        super().__init__(position, width, height)
        self.background = (44, 44, 44, 160)
        self.is_visible = False
        self.panel = UIPanel((position[0] + 50, position[1]), width - 100, height - 100)
        self.panel.background = (255, 255, 255)
        self.label = UILabel(message, (20, 20), 200, 40)
        self.label.auto_size = True
        self.confirm_button = UIButton("Confirm", (50, 80), 100, 40)
        self.confirm_button.click_event = self.__on_confirm
        self.cancel_button = UIButton("Cancel", (160, 80), 100, 40)
        self.cancel_button.click_event = self.__on_cancel
        self.panel.add_child(self.label)
        self.panel.add_child(self.confirm_button)
        self.panel.add_child(self.cancel_button)
        self.add_child(self.panel)
        self.action_confirmed = False

    def show(self):
        self.is_visible = True

    def __on_confirm(self, sender):
        self.action_confirmed = True
        self.is_visible = False

    def __on_cancel(self, sender):
        self.action_confirmed = False
        self.is_visible = False