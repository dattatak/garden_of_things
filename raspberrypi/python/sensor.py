#!/usr/bin/env python3

import serial
import sys
import sqlite3
import time

test = serial.Serial("/dev/ttyAMA0", 9600, timeout = 5)
conn = sqlite3.connect("/var/www/sensors.db")

c = conn.cursor()
c.execute("SELECT sensorID FROM Sensors")
tempSensors = c.fetchall();

content = 's'
received = False
reply = []

for sensorID in tempSensors:
	test.open()
	content += str(sensorID[0])
	content +=b'\n'
	print content
	test.write(content)
	while received == False:
		try:
				reply.append(test.readline())
				received = True
		except:
				pass
	sensorValue = reply[0][:5]
	print reply[0][:5]
	c.execute("INSERT INTO sensorValues values(time('now','localtime'),date('now'),?,?)", (sensorID[0], sensorValue))
	conn.commit()
	test.close()
	content = 's'
	received = False
	reply = []

conn.close()
test.close()

