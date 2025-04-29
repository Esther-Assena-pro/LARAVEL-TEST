# manages our individual quadrans orientation

class Quadrant:
    def __init__(self,id,recto,verso):
        self.id=id
        self.recto=recto
        self.verso=verso
        self.current_face=recto

    def swap_orientation(self):
        if self.current_face == self.verso:
            self.current_orientation = self.recto
        else:
            self.current_orientation = self.verso

    def get_current_face(self):
        return self.current_face       