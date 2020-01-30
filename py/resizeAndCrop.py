import cv2
import numpy as np

#Load up pre-processed image
img = cv2.imread('../img/pre_proc/image1.jpg', 0)

#Resize image
# scale_percent = 20;
# width = int(img.shape[1] * scale_percent/100)
# height = int(img.shape[0] * scale_percent/100)
# img = cv2.resize(img, (width, height), interpolation = cv2.INTER_AREA)

#Crop image
cropped = img[1428:1528, 2018:2138]
# cropped=cv2.resize(cropped, (width, height), interpolation = cv2.INTER_AREA)
# cv2.imshow('small, cropped image', cropped)

#Write new image and exit
cv2.imwrite('../img/pre_proc/test.jpg', cropped)
cv2.imshow('../img/pre_proc/test.jpg', cropped)
cv2.waitKey(0)
