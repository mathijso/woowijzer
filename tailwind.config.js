/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Livewire/**/*.php",
    ],
    theme: {
        extend: {
            colors: {
                // Rijksoverheid kleuren
                'rijksblauw': '#154273',      // Lintblauw - primaire kleur
                'rijkscyaan': '#007bc7',      // Hemelblauw - secundaire kleur
                'rijkspaars': '#42145f',      // Paars
                'rijksviolet': '#a90061',     // Violet
                'rijksrood': '#ca005d',       // Robijnrood
                'rijksroze': '#f092cd',       // Roze
                'rijksoranje': '#e17000',     // Oranje
                'rijksgeel': '#ffb612',       // Donkergeel
                'rijksgroen': '#39870c',      // Groen
                'rijksdonkerblauw': '#01689b', // Donkerblauw
                'rijkslichtblauw': '#8fcae7', // Lichtblauw
                'rijksmint': '#76d2b6',       // Mintgroen
                // Grijstinten
                'rijksgrijs': {
                    1: '#f3f3f3',
                    2: '#e6e6e6',
                    3: '#cccccc',
                    4: '#b4b4b4',
                    5: '#999999',
                    6: '#696969',
                    7: '#535353',
                }
            },
            fontFamily: {
                'sans': ['RijksoverheidSans', 'Arial', 'sans-serif'],
                'rijks': ['RijksoverheidSans', 'Arial', 'sans-serif'],
                'rijks-serif': ['RijksoverheidSerif', 'Times New Roman', 'serif'],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            }
        },
    },
    plugins: [],
}
