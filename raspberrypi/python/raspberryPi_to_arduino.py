import serial
import sys
import sqlite3
import time

class arduino_comm():
    configure_control_signal = b"c"
    configure_sensor_signal = b"s"
    control_signal = b"o"
    request_moisture_signal = b"m"
    request_temperature_signal = b"t"
	
    def __init__(self):
        self.serial_port = serial.Serial("/dev/ttyAMA0", 9600, timeout = 5)
        
    
    def serial_message(self, message, reply_size):
        self.serial_port.open()
        #print("Sending {}".format(message))
        for character in message:
                self.serial_port.write(character)
        #print(reply_size)
        while self.serial_port.inWaiting == 0:
            pass 
        reply = self.serial_port.readline()

        self.serial_port.close()

        #print(int(reply, 16))

        return int(reply, 16)
		
    def configure_sensor_pin(self, supply_pin, pin_number):
        message = []
        message.append(self.configure_sensor_signal)
        message.append(bytes(2))
        message.append(chr(supply_pin))
        message.append(chr(pin_number))
        
        return message, 1
    
    def configure_control_pin(self, pin_number):
        message = []
        message.append(self.configure_control_signal)
        message.appendbytes([pin_number])
        
        return message, 1
        
    def signal_control(self, pin_number, highlow):
        message = []
        message.append(self.control_signal)
        message.append(bytes(2))
        message.append(chr(highlow))
        message.append(chr(pin_number))
        
        return message, 1
        
    def request_moisture(self, pin_number):
        message = []
        message.append(self.request_moisture_signal)
        message.append(bytes(1))
        message.append(chr(pin_number))
        
        return message, 2
        
    def request_temperature(self, pin_number):
        message = ""
        message += self.request_temperature_signal
        message += bytes([1])
        message += bytes([pin_number])
        
        return message, 2

if __name__ == "__main__":
	pass