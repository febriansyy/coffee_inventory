def pohon():
    x = "^"
    y = " "
    
    for i in range(0, 20, 2):
        if i == 0:
            print("         *")
        print(y * (10 - int(i / 2)) + 
             (x * i) + x + y *
             (10 - int(i/2)))
    print("_________||__________\n")
        
pohon()