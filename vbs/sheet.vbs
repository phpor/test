Sub test()
    Dim i
    Dim sheet As Worksheet
    
    Set sheet = Application.Worksheets("Sheet1")
    
    For i = 2 To sheet.Rows.Count - 1
        If sheet.Cells(i, 1) = "" Then
            Exit For
        End If
        
        sheet.Cells(i, 2) = get_user_by_area(sheet.Cells(i, 1))
    Next i

End Sub

Public Function get_user_by_area(area As String) As String

    Dim dict, keys, i, pos, cnt, sheet As Worksheet
    
    '创建一些变量
    Set dict = CreateObject("Scripting.Dictionary")
    dict.RemoveAll
    
    cnt = Application.Worksheets.Count
    For i = 1 To cnt
    Set sheet = Application.Worksheets.Item(i)
        If sheet.Name = "对照表" Then
            Exit For
        End If
    Next i
    
    If sheet.Name <> "对照表" Then
        MsgBox "没有对照表"
        Exit Function
        
    End If
    
    For i = 2 To sheet.Cells.Rows.Count
        If sheet.Cells(i, 1) = "" Then
            Exit For
        End If
        
        MsgBox sheet.Cells(i, 1) & ":" & sheet.Cells(i, 2)
        dict.Add sheet.Cells(i, 2), sheet.Cells(i, 1)
        
    Next i
    
    
    Exit Function
    

   ' dict.Add "南阳", "石秋丽"     '添加一些关键字和条目。

    
    keys = dict.keys              '获得关键字
    For i = 0 To dict.Count - 1 '遍及数组
        pos = InStr(area, keys(i))
        If pos > 0 Then
            get_user_by_area = dict.Item(keys(i))
            Exit For
        End If
        
       'MsgBox pos
      ' MsgBox (dict.Item(keys(i)))          '打印关键字
    Next i
    
End Function
