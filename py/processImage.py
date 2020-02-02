import json
import os
import os.path
import cv2
import mysql.connector
import numpy as np
import copy
from connect import connection

# fetch the id's of queued tasks from the database
try:
    cursor = connection.cursor()
    sql = "SELECT taskId FROM Task WHERE taskState = 0"
    cursor.execute(sql)
    task_data = cursor.fetchall();
    cursor.close()

    if len(task_data) == 0:
        print('There are no queued tasks in the database!')
        quit()

except Exception as e:
    print(e)
    quit()

# look for the first folder that matches the id of an incomplete task (temporary)
def first_queued_folder(input_path):
    for subdir in os.listdir(input_path):
        if os.path.isdir(os.path.join(input_path, subdir)):
            for task in task_data:
                if (str(task[0]) == subdir):
                    return os.path.join(input_path, subdir)
    return None

input_subdir = first_queued_folder("../input/")

if input_subdir is None:
    print('There are no uploaded files for the queued task(s)!')
    quit()

# find and load the image
for file in os.listdir(input_subdir):
    if file.endswith(".jpg"):
        img = cv2.imread(os.path.join(input_subdir, file))
        break

if img is None:
    print('There is no jpeg image in the subfolder named, \"' + input_subdir + '\"!')
    quit()

# find and load the gauge data
for file in os.listdir(input_subdir):
    if file.endswith(".json"):
        json_name = file
        break

if json_name is None:
    print('There is no json file in the subfolder named, \"' + input_subdir + '\"')
    quit()

# unpack the JSON file
taskId = None
userId = None
boxes = []
with open(os.path.join(input_subdir, json_name), 'r') as json_file:
    incomingData = json.load(json_file)
    for key, value in incomingData.items():
        if key == 'taskId':
            taskId = value
        elif key == 'userId':
            userId = value
        else:
            boxes.append(value)

# tell the database that we've started processing
try:
    cursor = connection.cursor()
    sql = "UPDATE Task SET taskState = 0 WHERE taskId = " + taskId
    cursor.execute(sql)
    connection.commit()
    cursor.close()

except Exception as e:
    print(e)
    quit()

width, height =  img.shape[:2]
gauges=[]
for box in boxes:
    gaugeType=str(box.get('gaugeType'))
    cx1=int(float(box.get('coordinates').get('start_x'))*height)
    cy1=int(float(box.get('coordinates').get('start_y'))*width)
    cx2=int(float(box.get('coordinates').get('end_x'))*height)
    cy2=int(float(box.get('coordinates').get('end_y'))*width)
    print(cy1)
    gauges.append([gaugeType,cx1,cy1,cx2,cy2])
print(gauges)

black=cv2.THRESH_BINARY_INV
white=cv2.THRESH_BINARY
gauge_list={}
gauge_list["fuel_quantity"] = [20, 330,0,16,"lbs*100",white]
gauge_list["knots"]= [90, 110,0,150,"degrees",white]
gauge_list["eng"]= [80, 120,0,120,"degrees",white]
gauge_list["torque"]= [240, 300,1,9,"degrees",white]
gauge_list["fuel_psi"]= [300, 60,0,50,"degrees",white]


def p_distance(x1, y1, x2, y2):
    return np.sqrt((x2 - x1)**2 + (y2 - y1)**2)

def ReadGauge(img,cx1,cy1,cx2,cy2,gaugeType):
    cropped = img[cy1:cy2, cx1:cx2]
    img=cropped
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    img2=img
    col, row = img.shape[:2]
    circles = cv2.HoughCircles(gray, cv2.HOUGH_GRADIENT, 1, 20, np.array([]), 100, 50, int(col*0.20), int(row*0.60)).astype("int")
    inner = len(circles[0])
    x,y,r = 0,0,0
    for i in range(inner):
            x = x + circles[0][i][0]
            y = y + circles[0][i][1]
            r = r + circles[0][i][2]
    x = int(x/(inner))
    y = int(y/(inner))
    r = int(r/(inner))
    separator = 10
    linecounter = int(360 / separator)
    inside = np.zeros((linecounter,2))
    outside = np.zeros((linecounter,2))
    for i in range(0,linecounter):
                inside[i][0] = x + 0.9 * r * np.cos(separator * i * 3.14 / 180)
                inside[i][1] = y + 0.9 * r * np.sin(separator * i * 3.14 / 180)
                outside[i][0] = x + r * np.cos(separator * i * 3.14 / 180)
                outside[i][1] = y + r * np.sin(separator * i * 3.14 / 180)
    for i in range(0,linecounter):
        cv2.line(img2, (int(inside[i][0]), int(inside[i][1])), (int(outside[i][0]), int(outside[i][1])),(255, 0, 0), 2)

    # cv2.imshow('circlestest', img2)

    x = circles[0][0][0]
    y = circles[0][0][1]
    r = circles[0][0][2]

    black=cv2.THRESH_BINARY_INV
    white=cv2.THRESH_BINARY

    # needs work
    gaugeid=""
    gauge_name=""
    for key in gauge_list:
        if key==gaugeType:
            gaugeid=key
    gauge_name= gaugeid
    min_angle = gauge_list[gaugeid][0]
    max_angle = gauge_list[gaugeid][1]
    min_value = gauge_list[gaugeid][2]
    max_value = gauge_list[gaugeid][3]
    units = gauge_list[gaugeid][4]
    linecolor=gauge_list[gaugeid][5]

    degree_range = (max_angle - min_angle)
    value_range = (max_value - min_value)
    #threshold for finding lines in image
    # retval=150
    retval=140
    #for white lines
    th, dst2 = cv2.threshold(gray, retval, 255, linecolor)
    #for black lines
    # th, dst2 = cv2.threshold(gray, 140, 255, cv2.THRESH_BINARY_INV)
    #detect 3 or more lines
    rho=3
    #for radians to degrees
    theta=np.pi/180
    threshold=100
    minLineLength = 10
    #should be no gap between lines
    maxLineGap = 0
    lines = cv2.HoughLinesP(image=dst2, rho=rho, theta=theta, threshold=threshold,minLineLength=minLineLength, maxLineGap=maxLineGap)
    for i in range(0, len(lines)):
      for x1, y1, x2, y2 in lines[i]:
         cv2.line(img, (x1, y1), (x2, y2), (0, 0, 255), 2)
    #min distance from center
    #0.0025 to 0.20
    p1min = 0.025*r
    p1max = 0.20*r
    # print('p1min:',p1min)
    # print('p1max:',p1max)
    #min distance from outer part of circle
    #quarter to full circle
    p2min = 0.5*r
    p2max = 1.0*r
    plines = []
    for i in range(0, len(lines)):
        for i1, j1, i2, j2 in lines[i]:
            # print('x1: ',x1,' y1: ',y1,' x2: ',x2,' y2: ',y2)
            p_distance1 = p_distance(x, y, i1, j1)
            # print('p_distance1:', p_distance1)
            p_distance2 = p_distance(x, y, i2, j2)
            # print('p_distance2:',p_distance2)
            if (p_distance1 > p_distance2):
                temp = p_distance1
                p_distance1 = p_distance2
                p_distance2 = temp
            #differences must be within range
            if ((p1min<p_distance1<p1max) and (p2min<p_distance2<p2max)):
                line_length = p_distance(i1, j1, i2, j2)
                plines.append([i1,j1,i2,j2])
    try:
        x1 = plines[0][0]
        y1 = plines[0][1]
        x2 = plines[0][2]
        y2 = plines[0][3]
    except IndexError as e:
        print('some data could not be found')

    # x1 = plines[1][0]
    # y1 = plines[1][1]
    # x2 = plines[1][2]
    # y2 = plines[1][3]
    #draw red line on each changed line
    cv2.line(gray, (x1, y1), (x2, y2), (255, 0, 0), 1)
    # cv2.imwrite('../img/processed/test3.jpg', img)
    # cv2.imshow('../img/processed/test3.jpg', img)
    #2 distance of line from center to p1
    p1 = p_distance(x, y, x1, y1)
    #2 distance of line from center to p2
    p2 = p_distance(x, y, x2, y2)
    if (p1 > p2):
        xcos = x1 - x
        ysin= y - y1
    else:
        xcos = x2 - x
        ysin= y - y2
    #tan=(y/x) then turn into degrees
    res = np.rad2deg(np.arctan(float(ysin)/float(xcos)))
    # print(xcos)
    # print(ysin)
    # print('y/x in radians: ',np.arctan(float(ysin)/float(xcos)))
    # print('res: ',res)
    #angle from 0 to
    pointer_angle=270-res
    if xcos > 0 and ysin> 0:
        pointer_angle = 270 - res
    #90 to 180
    if xcos < 0 and ysin> 0:
        pointer_angle = 90 - res
    #180 to 270
    if xcos < 0 and ysin< 0:
        pointer_angle = 90 - res
    #270 to 360
    if xcos > 0 and ysin< 0:
        pointer_angle = 270 - res
    reading = (((pointer_angle - min_angle) * value_range) / degree_range) + (min_value)
    return reading,gauge_name

def sendData(taskId,userId,output):
    # write output to a new json file
    with open('../output/' + taskId + '.json', 'w') as outfile:
        json.dump(output, outfile)

    # tell the database that we've finished processing
    try:
        cursor = connection.cursor()
        sql = "UPDATE Task SET taskState = 2 WHERE taskId = " + taskId
        cursor.execute(sql)
        connection.commit()

    except Exception as e:
        print(e)

    finally:
        cursor.close()
        connection.close()
output=[]
for i in range(0,len(gauges)):
    gaugeType=gauges[i][0]
    print(gaugeType)
    cx1=gauges[i][1]
    cy1=gauges[i][2]
    cx2=gauges[i][3]
    cy2=gauges[i][4]
    valueAnalysis,nameAnalysis=ReadGauge(img,cx1,cy1,cx2,cy2,gaugeType)
    output.append({'value' : valueAnalysis,'gaugeType' : nameAnalysis,'taskId' : taskId,'userId': userId,})
sendData(taskId,userId,output)
#SEND DATA
