document.addEventListener('DOMContentLoaded', () => {
    // 選取彈出視窗元素
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    const closeCreateModal = document.getElementById('closeCreateModal');
    const closeEditModal = document.getElementById('closeEditModal');

    // 確保彈出視窗元素已經正確取得
    if (!createModal || !editModal) {
        console.error("Modal elements are not found in the document.");
        return;
    }

    // 新增按鈕的功能
    document.getElementById('createButton').addEventListener('click', () => {
        console.log("Create button clicked.");
        createModal.style.display = 'flex'; // 顯示新增彈出視窗
    });

    // 關閉新增視窗
    closeCreateModal.addEventListener('click', () => {
        createModal.style.display = 'none'; // 隱藏新增彈出視窗
    });

    // 關閉編輯視窗
    closeEditModal.addEventListener('click', () => {
        editModal.style.display = 'none'; // 隱藏編輯彈出視窗
    });

    // 點擊編輯按鈕的功能 (事件委派)
    document.querySelector('table').addEventListener('click', (event) => {
        if (event.target.classList.contains('edit-button')) {
            const studentId = event.target.dataset.id; // 獲取學生 ID
            const studentData = getStudentDataById(studentId); // 獲取學生資料

            // 填充編輯表單資料
            document.getElementById('editStudentForm').elements['name'].value = studentData.name;
            document.getElementById('editStudentForm').elements['school'].value = studentData.school;
            document.getElementById('editStudentForm').elements['grade'].value = studentData.grade;
            document.getElementById('editStudentForm').elements['tel'].value = studentData.tel;
            document.getElementById('editStudentForm').elements['address'].value = studentData.address;
            document.getElementById('editStudentForm').elements['parent'].value = studentData.parent;
            document.getElementById('editStudentForm').elements['parentTel'].value = studentData.parentTel;

            // 多選班級處理
            const classOptions = document.getElementById('editStudentForm').elements['class'].options;
            Array.from(classOptions).forEach(option => {
                option.selected = studentData.class.includes(option.value);
            });

            // 顯示編輯彈出視窗
            editModal.style.display = 'flex';
        }

        // 點擊刪除按鈕的功能 (事件委派)
        if (event.target.classList.contains('delete-button')) {
            const studentId = event.target.dataset.id; // 獲取學生 ID
            
            // 顯示刪除確認視窗
            if (confirm("確定要刪除該學生資料嗎？")) {
                // 假設您會發送刪除請求到後端，這裡我們將其移除
                const row = event.target.closest("tr"); // 找到相應的學生資料行
                row.remove(); // 刪除學生資料行

                alert("學生資料已刪除！");
            }
        }
    });

    // 點擊視窗外部關閉彈出視窗
    window.addEventListener('click', (event) => {
        if (event.target === createModal) {
            createModal.style.display = 'none';
        } else if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });

    // 輸入學生資料的模擬函數，這個可以根據您的需求替換成後端 API 的調用
    function getStudentDataById(studentId) {
        // 模擬返回學生資料
        return {
            name: "陳雅萍",
            school: "北一女中",
            grade: "二年級",
            tel: "0928494839",
            address: "台北市中正區徐州路2號",
            class: ["高一A班"],
            parent: "陳淑華",
            parentTel: "0984987654"
        };
    }
});
