
        const performanceCtx = document.getElementById('studentPerformanceChart').getContext('2d');
        const studentPerformanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                datasets: [{
                    label: 'Média de Desempenho',
                    data: [7.5, 7.8, 8.0, 7.9, 8.2, 8.3, 8.4, 8.1, 8.0, 8.5, 8.6, 8.7],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgb(243, 171, 64)',
                    borderWidth: 2
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10 // Definir o valor máximo para 10, que seria a nota máxima
                    }
                }
            }
        });

        const revenueCtx = document.getElementById('revenueSourcesChart').getContext('2d');
        const revenueSourcesChart = new Chart(revenueCtx, {
            type: 'doughnut',
            data: {
                labels: ['Mensalidades', 'Doações', 'Eventos'],
                datasets: [{
                    label: 'Fontes de Receita',
                    data: [70, 20, 10],
                    backgroundColor: ['#F3AB40', '#FDDC0F', '#FFDA7F'],
                    hoverOffset: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
