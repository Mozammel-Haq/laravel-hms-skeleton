document.addEventListener("DOMContentLoaded", function () {
    // Initialize .snow-editor
    document.querySelectorAll(".snow-editor").forEach((el) => {
      new Quill(el, {
        theme: "snow",
        modules: {
          toolbar: [
            ["bold", "italic", "underline"],
            [{ header: [null, 1, 2, 3, 4, 5, 6] }],
            [{ list: "ordered" }, { list: "bullet" }],
            ["link", "image", "video"]
          ]
        }
      });
    });
  
    // Initialize #snow-editor if it exists
    const snowEditorEl = document.querySelector("#snow-editor");
    if (snowEditorEl) {
      new Quill(snowEditorEl, {
        theme: "snow",
        modules: {
          toolbar: [
            [{ font: [] }, { size: [] }],
            ["bold", "italic", "underline", "strike"],
            [{ color: [] }, { background: [] }],
            [{ script: "super" }, { script: "sub" }],
            [{ header: [null, 1, 2, 3, 4, 5, 6] }, "blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ["direction", { align: [] }],
            ["link", "image", "video"],
            ["clean"]
          ]
        }
      });
    }
  
    // Initialize #bubble-editor if it exists
    const bubbleEditorEl = document.querySelector("#bubble-editor");
    if (bubbleEditorEl) {
      new Quill(bubbleEditorEl, {
        theme: "bubble"
      });
    }
  });
  