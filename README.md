<<<<<<< HEAD
# 113-1_DB_final_project
=======
# 113-1 資料庫管理 - MathSchool 專案



## 專案簡介

當提到「補習班管理」，傳統方式讓資料分散，難以快速查詢，管理人員和老師疲於應對，家長與學生也感到不便。
為了解決這些問題， **Math School** 設計了一套全新的資料管理系統。

:link: **[展示影片連結](https://youtu.be/0vxW5kzTje4)**



## 使用者功能

### Workers功能
在本系統中，workers 可以執行以下功能：
新增學生資料：workers可以通過輸入學生的名稱、學校、電話、地址等資料來新增學生。新增後，系統會自動分配一個唯一的學生編號以便後續識別。


查詢學生資料：Workers可通過學生的姓名或編號來查詢學生資料，快速檢索並查看學生的詳細資訊。
查詢可加入的讀書會：使用者可查詢尚未結束且可參與的讀書會。 

更新學生資料：Workers可對已新增的學生資料進行更新，包括學生的聯絡方式、學校等基本信息，並實時反映在系統中。


新增、查詢、更新教師資料：Workers可新增、查詢或更新教師的基本資料，管理教師的姓名、授課科目等信息。

新增、查詢、更新mentor資料：Workers可新增、查詢或更新導師（mentor）的資料，並管理其負責的學生及服務信息。

新增、查詢、更新試聽（audit）資料：Workers可新增、查詢或更新試聽紀錄，監控系統內的操作行為，保證管理過程中的資料安全與審核合規。

管理教室使用：Workers可安排並管理各班的上課教室，確保教室使用效率和課程安排的合理性。

管理學生課表：Workers可管理學生的課程安排，確定學生參加的各個班級，並確保班級和老師時間表的協調。

管理輔導老師班表：Workers可管理老師的授課班級及排課信息，確保老師的時間表與課程安排的精確對接。
- 

####  Student功能

在本系統中，Student 可以執行以下功能：
查詢mentor shift資料：Student可查詢輔導老師（mentor）的排班資訊，包括導師的上班時間。此功能有助於學生了解導師的可用時間，並安排相應的學習計劃。

查詢課程資料：Student可查詢班級（Class）、教師（TEACH)的資訊。此功能有助於學生了解自己的上課相關資訊，並安排相應的學習計劃。

- 
####  Mentor功能
查詢mentor shift資料：Mentor可查詢自身的排班資料，包括每日的工作時間、輔導時段以及與學生的輔導安排，確保時間管理的有效性和準確性。
   
- **交易管理**：

  - 可參考 `./student.php`。

- **併行控制**:

  - 可參考 `./student.php`。

  

## 程式說明

1. **`web.php`**
   - 系統主頁
2. **各種`./ dashboard.php`**
   - 給不同使用者的畫面
3. **`./modules`資料夾**
   - 各種功能的使用頁面。
4. **`./templates` 資料夾**
   - 頁面header。
   - 頁面footer。
5. **`./css` 資料夾**
   - 前端設計樣式。



## 開發環境

- PHP

- PostgreSQL: 16.4

## 啟動流程
1. 執行final_project->sql->create-table.sql
2. 將math_school.backup restore至postgreSQL
2. cd 到本final_project的資料夾
1. 在終端機開啟php -S localhost:<your_port>
2. 在瀏覽器開啟 localhost:<your_port>/web.php

  

## 參考資料

- README 說明文件及報告內容來自 **113-1資料庫管理**

>>>>>>> 75cf560 (Initial commit for DB final project)
