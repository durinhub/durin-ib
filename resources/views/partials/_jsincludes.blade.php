<script src="/js/pagescript.js"></script>


@if(Auth::check() && Auth::user()->canDo(App\Enums\AdminRightsEnum::CreateBlacklistedStrings))
    <style>
        #context-menu {
            display: none;
            position: fixed;
            z-index: 1000;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
            box-shadow: 0 2px 8px #0003;
        }

        #context-menu button {
            border: none;
            background: white;
            padding: 10px 15px;
            cursor: pointer;
            text-align: left;
        }

        #context-menu button:hover {
            background: #eee;
        }

        #modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: #0008;
            align-items: center;
            justify-content: center;
        }

        #modal.open {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 8px;
            width: min(400px, 90vw);
        }

        .modal-content input {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin: 12px 0;
        }
    </style>

<div id="context-menu">
        <button type="button" id="use-selection">
            Blacklistar String
        </button>
    </div>

    <div id="modal" role="dialog"
         aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-content">
            <h2 id="modal-title">Blacklistar nova string</h2>

            <form action="{{ route('blacklist.store') }}" method="POST">
                @csrf
                <label for="selected-text">Texto selecionado:</label>
                <input
                    type="text"
                    id="selected-text"
                    name="conteudo"
                    required
                >

                <button type="submit" class="form-control btn mini-btn">Submit</button>
                <button type="button" id="close-modal" class="form-control btn mini-btn-revert">
                    Cancel
                </button>
            </form>
        </div>
    </div>


<script>
        const contextMenu =
            document.getElementById('context-menu');

        const modal = document.getElementById('modal');

        const textInput =
            document.getElementById('selected-text');

        let selectedText = '';

        document.addEventListener('contextmenu', event => {
            const selection = window.getSelection();
            selectedText = selection
                ? selection.toString().trim()
                : '';

            if (!selectedText) {
                contextMenu.style.display = 'none';
                return; // Keep the native context menu
            }

            event.preventDefault();

            contextMenu.style.display = 'block';

            // Keep the menu inside the viewport
            contextMenu.style.left =
                Math.min(event.clientX, window.innerWidth - 180) + 'px';

            contextMenu.style.top =
                Math.min(event.clientY, window.innerHeight - 50) + 'px';
        });

        document.getElementById('use-selection')
            .addEventListener('click', () => {
                textInput.value = selectedText;
                contextMenu.style.display = 'none';
                modal.classList.add('open');
                textInput.focus();
                textInput.select();
            });

        document.getElementById('close-modal')
            .addEventListener('click', () => {
                modal.classList.remove('open');
            });

        document.addEventListener('click', event => {
            if (!contextMenu.contains(event.target)) {
                contextMenu.style.display = 'none';
            }
        });

        modal.addEventListener('click', event => {
            if (event.target === modal) {
                modal.classList.remove('open');
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                contextMenu.style.display = 'none';
                modal.classList.remove('open');
            }
        });
</script>

@endif