import math

class InterpolationFunction:
         @staticmethod
         def linear(t):
             return t

         @staticmethod
         def quadratic(t):
             return t * t

         @staticmethod
         def qubic(t):
             return t * t * t

         @staticmethod
         def smooth_step(t):
             return t * t * (3 - 2 * t)

         @staticmethod
         def sin(t):
             return math.sin(t * math.pi / 2)