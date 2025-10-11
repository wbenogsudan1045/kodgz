<script>
    document.addEventListener("DOMContentLoaded", () => {
        const notes = document.querySelectorAll(".note");
        const boardArea = document.getElementById("board-area");

        let activeNote = null;
        let offsetX, offsetY;
        let boardRect = boardArea.getBoundingClientRect();

        // 🧠 Recalculate board size if window resizes
        window.addEventListener("resize", () => {
            boardRect = boardArea.getBoundingClientRect();
        });

        notes.forEach(note => {
            note.addEventListener("mousedown", e => {
                activeNote = note;

                const rect = note.getBoundingClientRect();
                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;

                note.style.zIndex = 1000;
                note.style.transition = "none";
                e.preventDefault(); // prevent text selection
            });
        });

        document.addEventListener("mousemove", e => {
            if (!activeNote) return;

            // Current updated bounding box
            boardRect = boardArea.getBoundingClientRect();

            let x = e.clientX - boardRect.left - offsetX;
            let y = e.clientY - boardRect.top - offsetY;

            const noteRect = activeNote.getBoundingClientRect();
            const noteWidth = noteRect.width;
            const noteHeight = noteRect.height;

            // 🧩 Adjust for edges with padding safety
            const padding = 6; // small buffer to keep inside visually clean
            const maxX = boardRect.width - noteWidth - padding;
            const maxY = boardRect.height - noteHeight - padding;

            // Clamp note inside boundaries
            x = Math.max(padding, Math.min(x, maxX));
            y = Math.max(padding, Math.min(y, maxY));

            // Apply new coordinates
            activeNote.style.left = `${x}px`;
            activeNote.style.top = `${y}px`;
        });

        document.addEventListener("mouseup", () => {
            if (!activeNote) return;

            // Save final coordinates
            fetch(`/notes/${activeNote.dataset.id}/move`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    x: Math.round(parseFloat(activeNote.style.left) || 0),
                    y: Math.round(parseFloat(activeNote.style.top) || 0),
                })
            })
                .then(res => res.json())
                .then(data => console.log("✅ Position saved:", data))
                .catch(err => console.error("❌ Error saving position:", err));

            activeNote.style.zIndex = "";
            activeNote.style.transition = "all 0.1s ease-out";
            activeNote = null;
        });
    });
</script>