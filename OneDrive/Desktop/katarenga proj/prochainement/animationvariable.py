from prochainement.interpolationtype import InterpolationType
from prochainement.interpolationfunction import InterpolationFunction

class AnimationVariable:
    def __init__(self, start=0, end=0, interpolation_type=InterpolationType.SmoothStep, func=None):
        self.__start = start
        self.__end = end
        self.__t = 0.0
        self.__interpolation_type = interpolation_type
        self.__func = func
        if self.__interpolation_type == InterpolationType.Custom and func is None:
            raise ValueError("Custom interpolation requires a function")
        self.__value_changed_event = None

    @property
    def value(self):
        function = self.__get_interpolation_function()
        return self.__start + (self.__end - self.__start) * function(self.__t)

    @property
    def t(self):
        return self.__t

    @t.setter
    def t(self, value):
        if value > 1.0:
            value = 1.0
        elif value < 0.0:
            value = 0.0
        self.__t = value
        if self.__value_changed_event:
            self.__value_changed_event(self.__t)

    @property
    def start(self):
        return self.__start

    @start.setter
    def start(self, start):
        self.__start = start

    @property
    def end(self):
        return self.__end

    @end.setter
    def end(self, end):
        self.__end = end

    def reset(self):
        self.__t = 0.0

    def __get_interpolation_function(self):
        if self.__interpolation_type == InterpolationType.Linear:
            return InterpolationFunction.linear
        elif self.__interpolation_type == InterpolationType.Quadratic:
            return InterpolationFunction.quadratic
        elif self.__interpolation_type == InterpolationType.Cubic:
            return InterpolationFunction.qubic
        elif self.__interpolation_type == InterpolationType.SmoothStep:
            return InterpolationFunction.smooth_step
        elif self.__interpolation_type == InterpolationType.Sin:
            return InterpolationFunction.sin
        elif self.__interpolation_type == InterpolationType.Custom:
            return self.__func