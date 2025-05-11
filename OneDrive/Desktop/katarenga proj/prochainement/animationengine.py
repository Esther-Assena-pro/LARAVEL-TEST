import time
class AnimationEngine:
         __animations = []
         __current_time = 0
         __last_time = 0

         @classmethod
         def init(cls):
             cls.__current_time = 0
             cls.__last_time = 0

         @classmethod
         def add_animation(cls, animation):
             if animation in cls.__animations:
                 return
             cls.__animations.append(animation)
             return animation

         @classmethod
         def update(cls):
             if not cls.__animations:
                 return
             cls.__current_time = time.perf_counter_ns()
             if cls.__last_time != 0:
                 delta_time = cls.__current_time - cls.__last_time
                 animations = []
                 for animation in cls.__animations:
                     animation.advance_time(delta_time)
                 for animation in cls.__animations:
                     if not animation.is_finished:
                         animations.append(animation)
                 cls.__animations = animations
             cls.__last_time = cls.__current_time