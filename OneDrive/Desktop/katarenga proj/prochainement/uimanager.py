import pygame
from uielement import UIElement

class RedirectionSurface:
    def __init__(self, element_id, surface):
        self.element_id = element_id
        self.surface = surface
        self.is_invalidated = True

class UIManager:
    __ui_elements = []
    __redirection_surfaces = []
    __surface = None

    @classmethod
    def init(cls, surface):
        cls.__surface = surface

    @classmethod
    def handle_mouse_events(cls, event):
        for element in cls.__ui_elements:
            if not element.is_enabled or not element.is_visible:
                continue
            x, y = 0, 0
            if event.type == pygame.MOUSEBUTTONDOWN:
                x, y = event.pos
                if element.is_point_inside(x, y):
                    element.on_mouse_down(x - element.position[0], y - element.position[1])
            elif event.type == pygame.MOUSEBUTTONUP:
                x, y = event.pos
                if element.is_point_inside(x, y):
                    element.on_mouse_up(x - element.position[0], y - element.position[1])
                    if hasattr(element, "click_event"):
                        element.click_event(element)
            elif event.type == pygame.MOUSEMOTION:
                x, y = event.pos
                if element.is_point_inside(x, y):
                    element.on_mouse_move(x - element.position[0], y - element.position[1])
                    if not element.is_mouse_over:
                        element.is_mouse_over = True
                        element.on_mouse_enter()
                elif element.is_mouse_over:
                    element.is_mouse_over = False
                    element.on_mouse_leave()

    @classmethod
    def register_ui_element(cls, ui_element):
        if not isinstance(ui_element, UIElement):
            return
        internal_surface = pygame.Surface((ui_element.width, ui_element.height), pygame.SRCALPHA)
        ui_element.element_id = id(ui_element)
        cls.__ui_elements.append(ui_element)
        cls.__redirection_surfaces.append(RedirectionSurface(ui_element.element_id, internal_surface))
        cls.__sort_ui_elements()
        # Enregistrer également les enfants existants
        for child in ui_element.children:
            cls.register_ui_element(child)

    @classmethod
    def invalidate_element(cls, element):
        redirection_surface = cls.__get_redirection_surface(element.element_id)
        if redirection_surface:
            redirection_surface.is_invalidated = True

    @classmethod
    def set_element_opacity(cls, element_id, opacity):
        for redirection_surface in cls.__redirection_surfaces:
            if redirection_surface.element_id == element_id:
                redirection_surface.surface.set_alpha(opacity)

    @classmethod
    def update(cls):
        for ui_element in cls.__ui_elements:
            if not ui_element.is_enabled:
                continue
            redirection_surface = cls.__get_redirection_surface(ui_element.element_id)
            if redirection_surface and redirection_surface.is_invalidated:
                redirection_surface.is_invalidated = False
                redirection_surface.surface.fill((0, 0, 0, 0))
                ui_element.draw(redirection_surface.surface)
            for child in ui_element.children:
                child_redirection_surface = cls.__get_redirection_surface(child.element_id)
                if child_redirection_surface and child_redirection_surface.is_invalidated:
                    child_redirection_surface.is_invalidated = False
                    child_redirection_surface.surface.fill((0, 0, 0, 0))
                    child.draw(child_redirection_surface.surface)
                if child_redirection_surface:
                    redirection_surface.surface.blit(child_redirection_surface.surface, (child.position[0], child.position[1]))
            if redirection_surface:
                cls.__surface.blit(redirection_surface.surface, (ui_element.position[0], ui_element.position[1]))

    @classmethod
    def __get_redirection_surface(cls, element_id):
        for redirection_surface in cls.__redirection_surfaces:
            if redirection_surface.element_id == element_id:
                return redirection_surface
        return None

    @classmethod
    def __sort_ui_elements(cls):
        cls.__ui_elements.sort(key=lambda x: x.render_priority)