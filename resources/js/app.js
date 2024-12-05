import './bootstrap';
import Inputmask from "inputmask";

document.addEventListener("DOMContentLoaded", function () {
    // Inicialmente limpa a máscara
    const loginInput = document.getElementById("login");
    const loginType = document.getElementById("login-type");

    function applyMask() {
        const type = loginType.value;

        // Remove qualquer máscara aplicada
        Inputmask.remove(loginInput);

        if (type === "cpf") {
            Inputmask({
                mask: "999.999.999-99",
                placeholder: "_",
                clearMaskOnLostFocus: true,
            }).mask(loginInput);
        } else if (type === "phone") {
            Inputmask({
                mask: "(99) 9 9999-9999",
                placeholder: "_",
                clearMaskOnLostFocus: true,
            }).mask(loginInput);
        }
    }

    // Atualiza a máscara sempre que o tipo de login mudar
    loginType.addEventListener("change", applyMask);

    // Aplica a máscara inicial (Email não tem máscara)
    applyMask();
});


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
