import cv2
import numpy as np
import copy
img = cv2.imread('../img/pre_proc/test.jpg')
# img = cv2.imread('../img/pre_proc/gauge.jpg')
# img = cv2.imread('../img/pre_proc/aem.jpg')
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
def createmask(img,gray):
    circles = cv2.HoughCircles(gray, cv2.HOUGH_GRADIENT, 1, 10000, param1 = 50, param2 = 30, minRadius = 0, maxRadius = 0)
    height,width = gray.shape
    mask = np.zeros((height,width), np.uint8)
    for i in circles[0,:]:
        i[2]=i[2]+4
        cv2.circle(mask,(i[0],i[1]),i[2],(255,255,255),thickness=-1)
    masked_data = cv2.bitwise_and(img, img, mask=mask)
    _,thresh = cv2.threshold(mask,1,255,cv2.THRESH_BINARY)
    contours = cv2.findContours(thresh,cv2.RETR_EXTERNAL,cv2.CHAIN_APPROX_SIMPLE)
    x,y,w,h = cv2.boundingRect(contours[0])
    crop = masked_data[y:y+h,x:x+w]
    newgray = cv2.cvtColor(crop, cv2.COLOR_BGR2GRAY)
    return crop,newgray
img,gray=createmask(img,gray)
cv2.imshow('crop', img)
cv2.imshow('newgray', gray)
#circles = cv2.HoughCircles(img,cv2.HOUGH_GRADIENT,1,20,param1=100,param2=70,minRadius=0,maxRadius=0)
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

cv2.imshow('circlestest', img2)





x = circles[0][0][0]
y = circles[0][0][1]
r = circles[0][0][2]
print('x ',x)
print('y ',y)
print('r ',r)

#test
min_angle = 20
max_angle = 330
min_value = 0
max_value = 16
units = 'lbs*100'

#gauge
# min_angle = 45
# max_angle = 315
# min_value = 0
# max_value = 150
# units = 'psi'

#aem
# min_angle = 20
# max_angle = 330
# min_value = 0
# max_value = 9.8
# units = 'x100c'
degree_range = (max_angle - min_angle)
value_range = (max_value - min_value)
def p_distance(x1, y1, x2, y2):
    return np.sqrt((x2 - x1)**2 + (y2 - y1)**2)

def ReadGauge(img,gray, min_angle, max_angle, min_value, max_value, x, y, r,degree_range,value_range):
    #threshold for finding white lines in image for white
    # retval=150
    retval=140
    th, dst2 = cv2.threshold(gray, retval, 255, cv2.THRESH_BINARY)
    #for black
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
    print(p1min)
    p1max = 0.20*r
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
    x1 = plines[0][0]
    y1 = plines[0][1]
    x2 = plines[0][2]
    y2 = plines[0][3]

    # x1 = plines[1][0]
    # y1 = plines[1][1]
    # x2 = plines[1][2]
    # y2 = plines[1][3]
    #draw red line on each changed line
    cv2.line(gray, (x1, y1), (x2, y2), (255, 0, 0), 1)
    cv2.imwrite('../img/processed/test3.jpg', img)
    cv2.imshow('../img/processed/test3.jpg', img)
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
    print(xcos)
    print(ysin)
    print('y/x in radians: ',np.arctan(float(ysin)/float(xcos)))
    print('res: ',res)
    #angle from 0 to 90
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
    return reading
print('measurement:', ReadGauge(img,gray, min_angle, max_angle, min_value, max_value, x, y, r,degree_range,value_range),units)
cv2.waitKey(0)
