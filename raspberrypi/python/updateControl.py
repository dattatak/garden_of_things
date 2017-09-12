#!/usr/bin/env python3
import sqlite3
import threading
import time
import collections
import raspberryPi_to_arduino as arduinoServer

db_conn = sqlite3.connect("/var/www/sensors.db")
c = db_conn.cursor()

moisture_values_windows = {}

arduino_comm = arduinoServer.arduino_comm()

def update_averages(pin_number):
    c.execute("SELECT sample FROM moistureValues WHERE pinNumber = ? ORDER BY rowid DESC LIMIT 30 ", (pin_number,))
    rollingAverage = sum(zip(*c.fetchall())[0])
    print(rollingAverage)
    rollingAverage = rollingAverage/30
    print(rollingAverage)
    #rollingAverage = sum(moisture_values_windows[pin_number]) / len(moisture_values_windows[pin_number])
    #print("Updating moistureSensors SET rollingAverage = {} WHERE pinNumber = {}".format(rollingAverage, pin_number))
    c.execute("UPDATE moistureSensors SET rollingAverage = ? WHERE pinNumber = ?", (rollingAverage, pin_number))
    db_conn.commit()
    
def assign_control():
    c.execute("SELECT pinNumber, threshold, rollingAverage, controlPin FROM moistureSensors")
    moistureSensors = c.fetchall()
    #print("Assigning Control")
    for sensors in moistureSensors:
        if sensors[2] < sensors[1]:
            arduino_comm.serial_message(*arduino_comm.signal_control(sensors[3], 1))
            time.sleep(10)
            arduino_comm.serial_message(*arduino_comm.signal_control(sensors[3], 0))
        else:
            arduino_comm.serial_message(*arduino_comm.signal_control(sensors[3], 0))
            
c.execute("SELECT pinNumber FROM moistureSensors")
moistureSensors = c.fetchall()
for pin in moistureSensors: 
    update_averages(pin[0])
assign_control()