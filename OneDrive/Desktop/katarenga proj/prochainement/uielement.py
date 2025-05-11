class UIElement:
    def __init__(self, position, width, height):
        self.__position = position
        self.__width = width
        self.__height = height
        self.__children = []
        self.__is_enabled = True
        self.__is_visible = True
        self.__is_mouse_over = False
        self.__render_priority = 0
        self.__text = ""
        self.__font = None
        self.element_id = 0

    @property
    def position(self):
        return self.__position

    @position.setter
    def position(self, value):
        self.__position = value
        self.invalidate()

    @property
    def width(self):
        return self.__width

    @width.setter
    def width(self, value):
        self.__width = value
        self.invalidate()

    @property
    def height(self):
        return self.__height

    @height.setter
    def height(self, value):
        self.__height = value
        self.invalidate()

    @property
    def children(self):
        return self.__children

    @property
    def is_enabled(self):
        return self.__is_enabled

    @is_enabled.setter
    def is_enabled(self, value):
        self.__is_enabled = value
        self.invalidate()

    @property
    def is_visible(self):
        return self.__is_visible

    @is_visible.setter
    def is_visible(self, value):
        self.__is_visible = value
        self.invalidate()

    @property
    def is_mouse_over(self):
        return self.__is_mouse_over

    @is_mouse_over.setter
    def is_mouse_over(self, value):
        self.__is_mouse_over = value

    @property
    def render_priority(self):
        return self.__render_priority

    @render_priority.setter
    def render_priority(self, value):
        self.__render_priority = value

    @property
    def text(self):
        return self.__text

    @text.setter
    def text(self, value):
        self.__text = value
        self.invalidate()

    @property
    def font(self):
        return self.__font

    @font.setter
    def font(self, value):
        self.__font = value
        self.invalidate()

    def add_child(self, child):
        self.__children.append(child)
        child.element_id = id(child)

    def remove_child(self, child):
        self.__children.remove(child)

    def draw(self, surface):
        pass

    def update(self):
        pass

    def on_mouse_down(self, x, y):
        pass

    def on_mouse_move(self, x, y):
        pass

    def on_mouse_up(self, x, y):
        pass

    def on_mouse_enter(self):
        pass

    def on_mouse_leave(self):
        pass

    def is_point_inside(self, x, y):
        px, py = self.position
        return px <= x <= px + self.width and py <= y <= py + self.height

    def invalidate(self):
        from prochainement.uimanager import UIManager
        UIManager.invalidate_element(self)