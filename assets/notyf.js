import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

// Create an instance of Notyf
const notyf = new Notyf({
    duration: 5000,
    position :{
        x: 'right',
        y: 'top'
    },types:[{
        type:'info',
        background:'#00bfff',
        icon: false
    },{
    type: 'success',
    background: '#5cb85c', // Couleur de fond pour le succès
    icon: false
},{
        type:'warning',
        background:'#ffd70',
        icon: false
    }]

});
let messages = document.querySelectorAll('#notyf-message');
console.log(messages)