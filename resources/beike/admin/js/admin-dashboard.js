/**
 * 增强数据看板 JavaScript 功能
 * Enhanced Dashboard JavaScript Functions
 */
import http from "../../../js/http";

class EnhancedDashboard {
  constructor() {
    this.charts = {};
    this.currentStatsData = null; // 存储当前的统计数据
    this.themeColors = {
      light: {
        xyBorderColor: "rgba(0, 0, 0, 0.1)",
        xyTextColor: "#333",
      },
      dark: {
        xyBorderColor: "rgba(255, 255, 255, 0.2)",
        xyTextColor: "#ddd",
      }
    };
    this.init();
    this.updateChartThemes();
  }


  updateChartThemes(theme) {
    theme = theme || document.querySelector('html').getAttribute('data-bs-theme') || 'light';

    const colors = this.themeColors[theme];
    const chartPvUv = this.charts['pv-uv'];
    const chartSource = this.charts['source'];
    const chartFunnel = this.charts['funnel'];

    chartPvUv.options.scales.y.grid.color = colors.xyBorderColor;
    chartPvUv.update();

    chartSource.options.scales.x.grid.color = colors.xyBorderColor;
    chartSource.options.scales.y.ticks.color = colors.xyTextColor;
    chartSource.update();

    chartFunnel.options.scales.x.grid.color = colors.xyBorderColor;
    chartFunnel.options.scales.x.grid.borderColor = colors.xyBorderColor;
    chartFunnel.update();
  }

  init() {
    this.initTimeButtons();
    this.initChartTimeButtons();
    this.initConversionTypeSelect();
    this.initFunnelRefreshButton();
    this.initProductFilterButtons();
    this.initCharts();

    // 延迟加载数据，确保图表初始化完成
    setTimeout(() => {
      this.loadInitialData();
    }, 200);

    // this.startRealTimeUpdates(); // 禁用实时数据更新
  }

  // 时间维度按钮切换
  initTimeButtons() {
    const buttons = ['btn-today', 'btn-yesterday', 'btn-week'];

    buttons.forEach(buttonId => {
      const button = document.getElementById(buttonId);
      if (button) {
        button.addEventListener('click', (e) => {
          this.switchTimeButton(buttons, e.target);
          this.updateDashboardData(e.target.textContent);
        });
      }
    });
  }

  // 访客趋势图表时间维度按钮切换
  initChartTimeButtons() {
    const chartButtons = ['chart-btn-today', 'chart-btn-yesterday', 'chart-btn-week'];

    chartButtons.forEach(buttonId => {
      const button = document.getElementById(buttonId);
      if (button) {
        button.addEventListener('click', (e) => {
          this.switchTimeButton(chartButtons, e.target);
          this.updateChartData(e.target.textContent);
        });
      }
    });
  }

  // 按钮状态切换通用方法
  switchTimeButton(buttonIds, activeButton) {
    buttonIds.forEach(id => {
      const btn = document.getElementById(id);
      if (btn) {
        // 处理不同的按钮样式
        if (btn.classList.contains('dashboard-btn')) {
          // dashboard样式按钮
          btn.classList.remove('dashboard-btn-primary');
          btn.classList.add('dashboard-btn-secondary');
        } else {
          // 原有样式按钮
          btn.classList.remove('btn-primary');
          btn.classList.add('btn-default');
        }
      }
    });

    if (activeButton.classList.contains('dashboard-btn')) {
      // dashboard样式按钮
      activeButton.classList.remove('dashboard-btn-secondary');
      activeButton.classList.add('dashboard-btn-primary');
    } else {
      // 原有样式按钮
      activeButton.classList.remove('btn-default');
      activeButton.classList.add('btn-primary');
    }
  }

  // 转化率类型选择功能
  initConversionTypeSelect() {
    const conversionSelect = document.getElementById('conversion-type-select');
    const conversionRate = document.getElementById('conversion-rate');
    const conversionChange = document.getElementById('conversion-change');

    if (!conversionSelect || !conversionRate || !conversionChange) return;

    // 移除硬编码数据，等待API数据加载
    conversionSelect.addEventListener('change', (e) => {
      // 当用户切换转化率类型时，只更新转化率显示，不重新加载其他数据
      this.updateConversionDisplay(e.target.value);
    });

    // 初始化显示默认值，等待API数据更新
    conversionRate.textContent = '0%';
    conversionChange.textContent = '0%';
  }

  // 漏斗图刷新按钮功能
  initFunnelRefreshButton() {
    const refreshBtn = document.getElementById('funnel-refresh-btn');
    if (!refreshBtn) return;

    refreshBtn.addEventListener('click', (e) => {
      e.preventDefault();
      this.refreshFunnelData();
    });
  }

  // 商品过滤按钮功能
  initProductFilterButtons() {
    // 热销商品过滤按钮
    const hotFilterBtn = document.getElementById('hot-products-filter-btn');
    if (hotFilterBtn) {
      const dropdown = hotFilterBtn.closest('.dropdown');
      const menuItems = dropdown.querySelectorAll('.dropdown-item');

      menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          const sort = item.getAttribute('data-sort');

          if (sort) {
            this.applyProductSort('hot', sort);
          }
        });
      });
    }

    // 滞销商品过滤按钮
    const slowFilterBtn = document.getElementById('slow-products-filter-btn');
    if (slowFilterBtn) {
      const dropdown = slowFilterBtn.closest('.dropdown');
      const menuItems = dropdown.querySelectorAll('.dropdown-item');

      menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          const sort = item.getAttribute('data-sort');

          if (sort) {
            this.applyProductSort('slow', sort);
          }
        });
      });
    }
  }

  // 获取当前选择的时间范围
  getCurrentTimeRange() {
    const activeButton = document.querySelector('.time-range-btn.active');
    if (activeButton) {
      const timeRangeMap = {
        '今日': 'today',
        'Today': 'today',
        'Hoy': 'today',
        'Oggi': 'today',
        '오늘': 'today',
        'Сегодня': 'today',
        'Hari ini': 'today',
        '昨日': 'yesterday',
        'Yesterday': 'yesterday',
        'Ayer': 'yesterday',
        'Ieri': 'yesterday',
        '어제': 'yesterday',
        'Вчера': 'yesterday',
        'Kemarin': 'yesterday',
        '近7日': 'week',
        'Last 7 Days': 'week',
        'Últimos 7 días': 'week',
        'Ultimi 7 giorni': 'week',
        '최근 7일': 'week',
        'Последние 7 дней': 'week',
        '7 hari terakhir': 'week',
        '近7天': 'week'
      };
      return timeRangeMap[activeButton.textContent.trim()] || 'today';
    }
    return 'today';
  }

  updateConversionDisplay(type, data = null) {
    const conversionRate = document.getElementById('conversion-rate');
    const conversionChange = document.getElementById('conversion-change');

    if (!conversionRate || !conversionChange) return;

    // 如果没有提供数据，使用当前保存的数据
    const conversionData = data || this.currentStatsData;
    if (!conversionData || !conversionData.conversion) return;

    const typeData = conversionData.conversion[type];
    if (!typeData) return;

    conversionRate.textContent = `${typeData.rate}%`;
    conversionChange.textContent = `${Math.abs(typeData.change)}%`;

    const changeContainer = conversionChange.parentElement;
    const icon = changeContainer.querySelector('i');

    if (typeData.trend === 'up') {
      changeContainer.className = 'dashboard-stat-change dashboard-change-success';
      icon.className = 'bi bi-arrow-up';
    } else {
      changeContainer.className = 'dashboard-stat-change dashboard-change-danger';
      icon.className = 'bi bi-arrow-down';
    }
  }

  // 更新小图表
  updateMiniCharts() {
    Object.keys(this.charts).forEach(chartId => {
      if (chartId.includes('mini-')) {
        const chart = this.charts[chartId];
        const data = chart.data.datasets[0].data;

        // 移除第一个数据点，添加新的数据点
        data.shift();
        data.push(data[data.length - 1] + (Math.random() - 0.5) * 20);

        chart.update('none');
      }
    });
  }

  // 初始化图表
  initCharts() {
    this.initMiniCharts();
    this.initMainCharts();
    this.initFunnelChart();
  }

  // 初始化小型趋势图 - 完全按照dashboard.html样式
  initMiniCharts() {
    // 访客人数趋势图
    const visitorCtx = document.getElementById('visitor-chart');
    if (visitorCtx) {
      this.charts['visitor'] = new Chart(visitorCtx.getContext('2d'), {
        type: 'line',
        data: {
          labels: Array(12).fill(''),
          datasets: [{
            data: Array(12).fill(0), // 初始化为空数据
            borderColor: '#FF7D00',
            backgroundColor: 'rgba(255, 125, 0, 0.1)',
            borderWidth: 2,
            pointRadius: 0,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { display: false },
            y: { display: false }
          },
          elements: {
            line: { tension: 0.4 }
          }
        }
      });
    }

    // 加购人数趋势图
    const cartCtx = document.getElementById('cart-chart');
    if (cartCtx) {
      this.charts['cart'] = new Chart(cartCtx.getContext('2d'), {
        type: 'line',
        data: {
          labels: Array(12).fill(''),
          datasets: [{
            data: Array(12).fill(0), // 初始化为空数据
            borderColor: '#FF7D00',
            backgroundColor: 'rgba(255, 125, 0, 0.1)',
            borderWidth: 2,
            pointRadius: 0,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { display: false },
            y: { display: false }
          },
          elements: {
            line: { tension: 0.4 }
          }
        }
      });
    }

    // 购买人数趋势图
    const purchaseCtx = document.getElementById('purchase-chart');
    if (purchaseCtx) {
      this.charts['purchase'] = new Chart(purchaseCtx.getContext('2d'), {
        type: 'line',
        data: {
          labels: Array(12).fill(''),
          datasets: [{
            data: Array(12).fill(0), // 初始化为空数据
            borderColor: '#F53F3F',
            backgroundColor: 'rgba(245, 63, 63, 0.1)',
            borderWidth: 2,
            pointRadius: 0,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { display: false },
            y: { display: false }
          },
          elements: {
            line: { tension: 0.4 }
          }
        }
      });
    }

    // 转化率趋势图
    const conversionCtx = document.getElementById('conversion-chart');
    if (conversionCtx) {
      this.charts['conversion'] = new Chart(conversionCtx.getContext('2d'), {
        type: 'line',
        data: {
          labels: Array(12).fill(''),
          datasets: [{
            data: Array(12).fill(0), // 初始化为空数据
            borderColor: '#0FC6C2',
            backgroundColor: 'rgba(15, 198, 194, 0.1)',
            borderWidth: 2,
            pointRadius: 0,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { display: false },
            y: { display: false }
          },
          elements: {
            line: { tension: 0.4 }
          }
        }
      });
    }

    // 加载初始数据
    this.loadMiniChartData('today');
  }

  // 初始化主要图表
  initMainCharts() {
    this.initPvUvChart();
    this.initSourceChart();
  }

  // PV/UV趋势图 - 完全按照dashboard.html样式
  initPvUvChart() {
    const ctx = document.getElementById('pv-uv-chart');
    if (!ctx) return;

    this.charts['pv-uv'] = new Chart(ctx.getContext('2d'), {
      type: 'line',
      data: {
        labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
        datasets: [
          {
            label: 'PV',
            data: [12500, 13200, 12800, 14500, 15600, 18200, 17800],
            borderColor: '#FF7D00',
            backgroundColor: 'rgba(255, 125, 0, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#FF7D00',
            tension: 0.4,
            fill: true
          },
          {
            label: 'UV',
            data: [5200, 5400, 5300, 5800, 6100, 7200, 7000],
            borderColor: '#0FC6C2',
            backgroundColor: 'rgba(15, 198, 194, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#0FC6C2',
            tension: 0.4,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
              display: true,
              font: {
                size: 12
              },
              color: '#666',
              padding: 8,
              maxTicksLimit: 10
            }
          }
        }
      }
    });

    // 强制修复图例样式
    setTimeout(() => {
      this.fixLegendStyle();
    }, 100);
  }

  // 创建自定义图例
  fixLegendStyle() {
    const chart = this.charts['pv-uv'];
    if (!chart) return;

    const canvas = chart.canvas;
    const parent = canvas.parentNode;

    // 查找或创建图例容器
    let legendContainer = parent.querySelector('.custom-legend');
    if (!legendContainer) {
      legendContainer = document.createElement('div');
      legendContainer.className = 'custom-legend';
      legendContainer.style.cssText = `
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        padding: 0 10px;
      `;
      parent.insertBefore(legendContainer, canvas);
    }

    // 清空现有内容
    legendContainer.innerHTML = '';

    // 创建PV图例项
    const pvLegend = document.createElement('div');
    pvLegend.style.cssText = `
      display: flex;
      align-items: center;
      gap: 5px;
    `;

    const pvMarker = document.createElement('div');
    pvMarker.style.cssText = `
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #FF7D00;
      display: inline-block;
    `;

    const pvLabel = document.createElement('span');
    pvLabel.textContent = 'PV';

    pvLegend.appendChild(pvMarker);
    pvLegend.appendChild(pvLabel);

    // 创建UV图例项
    const uvLegend = document.createElement('div');
    uvLegend.style.cssText = `
      display: flex;
      align-items: center;
      gap: 5px;
    `;

    const uvMarker = document.createElement('div');
    uvMarker.style.cssText = `
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #0FC6C2;
      display: inline-block;
    `;

    const uvLabel = document.createElement('span');
    uvLabel.textContent = 'UV';

    uvLegend.appendChild(uvMarker);
    uvLegend.appendChild(uvLabel);

    // 添加到容器
    legendContainer.appendChild(pvLegend);
    legendContainer.appendChild(uvLegend);
  }

  // 客户来源分析图 - 水平条形图布局
  initSourceChart() {

    const ctx = document.getElementById('source-chart');
    if (!ctx) {
      console.error('source-chart canvas element not found');
      return;
    }


    try {
      // 初始化空图表，等待API数据加载

      this.charts['source'] = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [],
          datasets: [{
            data: [],
            backgroundColor: [],
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            barThickness: 20
          }]
        },
        options: {
          indexAxis: 'y', // 水平条形图
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              enabled: true,
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: 'rgba(255, 255, 255, 0.2)',
              borderWidth: 1,
              cornerRadius: 8,
              callbacks: {
                label: function(context) {
                  const label = context.label;
                  const value = context.parsed.x;
                  return `${label}: ${value}%`;
                }
              }
            }
          },
          scales: {
            x: {
              beginAtZero: true,
              max: 40,
              grid: {
                display: true,
                color: 'rgba(0, 0, 0, 0.1)',
                drawBorder: false
              },
              ticks: {
                color: '#666',
                font: {
                  size: 11
                },
                callback: function(value) {
                  return value + '%';
                }
              }
            },
            y: {
              grid: {
                display: false
              },
              ticks: {
                color: '#333',
                font: {
                  size: 12,
                  weight: '500'
                },
                padding: 8
              }
            }
          },
          animation: {
            duration: 1200,
            easing: 'easeOutQuart'
          },
          interaction: {
            intersect: false,
            mode: 'index'
          }
        }
      });

    } catch (error) {
      console.error('Error creating source chart:', error);
    }
  }

  // 下单漏斗图 - 完全按照dashboard.html样式
  initFunnelChart() {
    const ctx = document.getElementById('funnel-chart');
    if (!ctx) return;

    // 初始化空图表，等待API数据
    this.charts['funnel'] = new Chart(ctx.getContext('2d'), {
      type: 'bar',
      data: {
        labels: [
          window.dashboardTranslations.product_views,
          window.dashboardTranslations.unique_visitors,
          window.dashboardTranslations.cart_additions,
          window.dashboardTranslations.orders,
          window.dashboardTranslations.successful_payments
        ],
        datasets: [{
          data: [0, 0, 0, 0, 0], // 初始化为0，等待API数据
          backgroundColor: [
            '#FF7D00',
            '#0FC6C2',
            '#00B42A',
            '#FFB84D',
            '#F53F3F'
          ],
          borderRadius: 6,
          barThickness: 30
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y', // 横向柱形图
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const value = context.parsed.x.toLocaleString();
                return value;
              }
            }
          }
        },
        scales: {
          x: {
            beginAtZero: true,
            max: 1600,
            grid: {
              color: 'rgb(208, 10, 10)'
            },
            ticks: {
              callback: function(value) {
                return value;
              }
            }
          },
          y: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          }
        },
        layout: {
          padding: {
            right: 20,
            left: 10
          }
        }
      }
    });
  }

  // 统一的时间维度切换处理函数
  async switchTimeRange(timeRange, updateType = 'all') {
    const timeRangeMap = {
      '今日': 'today',
      'Today': 'today',
      'Hoy': 'today',
      'Oggi': 'today',
      '오늘': 'today',
      'Сегодня': 'today',
      'Hari ini': 'today',
      '昨日': 'yesterday',
      'Yesterday': 'yesterday',
      'Ayer': 'yesterday',
      'Ieri': 'yesterday',
      '어제': 'yesterday',
      'Вчера': 'yesterday',
      'Kemarin': 'yesterday',
      '近7日': 'week',
      'Last 7 Days': 'week',
      'Últimos 7 días': 'week',
      'Ultimi 7 giorni': 'week',
      '최근 7일': 'week',
      'Последние 7 дней': 'week',
      '7 hari terakhir': 'week',
      '近7天': 'week'
    };

    const mappedTimeRange = timeRangeMap[timeRange] || timeRange;

    // 显示加载状态
    this.showLoadingState();

    try {
      const promises = [];

      // 根据更新类型决定加载哪些数据
      if (updateType === 'all' || updateType === 'stats') {
        promises.push(this.loadStatsData(mappedTimeRange));
        promises.push(this.loadSourceData(mappedTimeRange));
        promises.push(this.loadFunnelData(mappedTimeRange));
        promises.push(this.loadProductRanking(mappedTimeRange));
        promises.push(this.loadMiniChartData(mappedTimeRange));
      }

      if (updateType === 'all' || updateType === 'charts') {
        promises.push(this.loadTrendData(mappedTimeRange));
      }

      // 等待所有数据加载完成
      await Promise.all(promises);

      // 隐藏加载状态
      this.hideLoadingState();

    } catch (error) {
      console.error('时间维度切换失败:', error);
      this.hideLoadingState();
      this.showErrorMessage('数据加载失败，请重试');
    }
  }

  // 更新仪表盘数据
  updateDashboardData(timeRange) {
    this.switchTimeRange(timeRange, 'stats');
  }

  // 更新图表数据
  updateChartData(timeRange) {
    this.switchTimeRange(timeRange, 'charts');
  }

  // 加载初始数据
  loadInitialData() {
    this.loadStatsData('today');
    this.loadTrendData('today');
    this.loadSourceData('today');
    this.loadFunnelData('today');
    this.loadProductRanking('today');
  }

  // 加载统计数据
  async loadStatsData(timeRange) {
    try {
      const response = await http.axiosApi.get(window.dashboardRoutes.stats, {
        params: {time_range: timeRange}
      });
      const result = response.data;

      if (result.success) {
        this.updateStatsDisplay(result.data);
      }
    } catch (error) {
      console.error('获取统计数据失败:', error);
    }
  }

  // 加载小型趋势图数据
  async loadMiniChartData(timeRange) {
    try {
      const miniChartsRoute = window.dashboardRoutes?.miniCharts;
      if (!miniChartsRoute) return;

      const response = await http.axiosApi.get(miniChartsRoute, {
        params: {time_range: timeRange}
      });
      const result = response.data;

      if (result.success) {
        this.updateMiniCharts(result.data);
      } else {
        console.error('Failed to load mini chart data:', result.message);
      }
    } catch (error) {
      console.error('Error loading mini chart data:', error);
    }
  }

  // 更新小型趋势图
  updateMiniCharts(data) {
    // 更新访客趋势图
    if (this.charts['visitor'] && data.visitor) {
      const visitorData = data.visitor.slice(-12); // 取最后12个数据点
      this.charts['visitor'].data.labels = Array(visitorData.length).fill(''); // 动态调整标签数量
      this.charts['visitor'].data.datasets[0].data = visitorData;
      this.charts['visitor'].update('none');
    }

    // 更新加购趋势图
    if (this.charts['cart'] && data.cart) {
      const cartData = data.cart.slice(-12); // 取最后12个数据点
      this.charts['cart'].data.labels = Array(cartData.length).fill(''); // 动态调整标签数量
      this.charts['cart'].data.datasets[0].data = cartData;
      this.charts['cart'].update('none');
    }

    // 更新购买趋势图
    if (this.charts['purchase'] && data.purchase) {
      const purchaseData = data.purchase.slice(-12); // 取最后12个数据点
      this.charts['purchase'].data.labels = Array(purchaseData.length).fill(''); // 动态调整标签数量
      this.charts['purchase'].data.datasets[0].data = purchaseData;
      this.charts['purchase'].update('none');
    }

    // 更新转化率趋势图
    if (this.charts['conversion'] && data.conversion) {
      const conversionData = data.conversion.slice(-12); // 取最后12个数据点
      this.charts['conversion'].data.labels = Array(conversionData.length).fill(''); // 动态调整标签数量
      this.charts['conversion'].data.datasets[0].data = conversionData;
      this.charts['conversion'].update('none');
    }
  }

  // 加载趋势数据
  async loadTrendData(timeRange) {
    try {
      const response = await http.axiosApi.get(window.dashboardRoutes.trends, {
        params: {time_range: timeRange}
      });
      const result = response.data;

      if (result.success) {
        this.updateTrendChart(result.data);
      }
    } catch (error) {
      console.error('获取趋势数据失败:', error);
    }
  }

  // 加载来源数据
  async loadSourceData(timeRange = 'today') {
    try {
      const response = await http.axiosApi.get(window.dashboardRoutes.source, {
        params: {time_range: timeRange}
      });
      const result = response.data;

      if (result.success) {
        this.updateSourceChart(result.data);
        this.updateSourceLegend(result.data);
      } else {
        console.error('获取来源数据失败:', result.message);
        this.showSourceError(window.dashboardTranslations.get_source_analysis_failed.replace(':message', result.message));
      }
    } catch (error) {
      console.error('获取来源数据失败:', error);
      this.showSourceError(window.dashboardTranslations.network_error || 'Network request failed, please check your connection');
    }
  }

  // 加载漏斗数据
  async loadFunnelData(timeRange) {
    try {
      const response = await http.axiosApi.get(window.dashboardRoutes.funnel, {
        params: {time_range: timeRange}
      });
      const result = response.data;

      if (result.success) {
        this.updateFunnelDisplay(result.data);
      }
    } catch (error) {
      console.error('获取漏斗数据失败:', error);
    }
  }

  // 刷新漏斗数据
  async refreshFunnelData() {
    const refreshBtn = document.getElementById('funnel-refresh-btn');
    const icon = refreshBtn.querySelector('i');

    // 显示加载状态
    icon.className = 'bi bi-arrow-clockwise';
    refreshBtn.disabled = true;

    try {
      const timeRange = this.getCurrentTimeRange();
      await this.loadFunnelData(timeRange);

      // 显示成功状态
      icon.className = 'bi bi-check2-circle text-success';
      setTimeout(() => {
        icon.className = 'bi bi-arrow-repeat fs-5';
        refreshBtn.disabled = false;
      }, 1000);

    } catch (error) {
      console.error('刷新漏斗数据失败:', error);

      // 显示错误状态
      icon.className = 'bi bi-exclamation-triangle-fill text-danger';
      setTimeout(() => {
        icon.className = 'bi bi-arrow-repeat fs-5';
        refreshBtn.disabled = false;
      }, 2000);
    }
  }

  // 应用商品排序
  applyProductSort(type, sortType) {
    const container = document.getElementById(`${type}-products-container`);
    if (!container) return;

    const products = Array.from(container.querySelectorAll('.dashboard-product-item'));
    if (products.length === 0) return;

    // 获取商品数据
    const productData = products.map(item => {
      const name = item.querySelector('.dashboard-product-name').textContent;
      const price = item.querySelector('.dashboard-price').textContent;
      const sales = item.querySelector('.dashboard-sales').textContent;
      const percentage = item.querySelector('.dashboard-progress-text').textContent;

      return {
        element: item,
        name: name,
        price: parseFloat(price.replace(/[^0-9.-]+/g, '')),
        sales: parseInt(sales.match(/\d+/)[0]),
        percentage: parseFloat(percentage.replace('%', ''))
      };
    });

    // 根据排序类型排序
    productData.sort((a, b) => {
      switch (sortType) {
        case 'sales-desc':
          return b.sales - a.sales;
        case 'sales-asc':
          return a.sales - b.sales;
        case 'price-desc':
          return b.price - a.price;
        case 'price-asc':
          return a.price - b.price;
        default:
          return 0;
      }
    });

    // 重新排列DOM元素
    productData.forEach(item => {
      container.appendChild(item.element);
    });
  }


  // 加载商品排行
  async loadProductRanking(timeRange) {
    try {
      const [hotResponse, slowResponse] = await Promise.all([
        http.axiosApi.get(window.dashboardRoutes.products.hot, {
          params: {time_range: timeRange}
        }),
        http.axiosApi.get(window.dashboardRoutes.products.slow, {
          params: {time_range: timeRange}
        })
      ]);

      const hotResult = hotResponse.data;
      const slowResult = slowResponse.data;

      // 修复：确保 data 存在再更新显示
      if (hotResult.success && hotResult.data) {
        this.updateProductDisplay('hot', hotResult.data);
      } else {
        this.updateProductDisplay('hot', []);
      }
      if (slowResult.success && slowResult.data) {
        this.updateProductDisplay('slow', slowResult.data);
      } else {
        this.updateProductDisplay('slow', []);
      }
    } catch (error) {
      console.error('获取商品排行失败:', error);
      // 修复：出错时显示空数据状态
      this.updateProductDisplay('hot', []);
      this.updateProductDisplay('slow', []);
    }
  }

  // 更新统计数据显示
  updateStatsDisplay(data) {
    // 保存当前统计数据
    this.currentStatsData = data;
    // 访客数据
    if (data.visitors) {
      const visitorElement = document.getElementById('visitor-count');
      if (visitorElement) {
        visitorElement.textContent = data.visitors.current.toLocaleString();
      }

      // 更新访客趋势指示器
      this.updateTrendIndicator('visitor', data.visitors);
    }

    // 加购数据
    if (data.carts) {
      const cartElement = document.getElementById('cart-count');
      if (cartElement) {
        cartElement.textContent = data.carts.current.toLocaleString();
      }

      // 更新加购趋势指示器
      this.updateTrendIndicator('cart', data.carts);
    }

    // 购买数据
    if (data.purchases) {
      const purchaseElement = document.getElementById('purchase-count');
      if (purchaseElement) {
        purchaseElement.textContent = data.purchases.current.toLocaleString();
      }

      // 更新购买趋势指示器
      this.updateTrendIndicator('purchase', data.purchases);
    }

    // 转化率数据
    if (data.conversion) {
      const conversionSelect = document.getElementById('conversion-type-select');
      const selectedType = conversionSelect ? conversionSelect.value : 'visitor-to-purchase';

      if (data.conversion[selectedType]) {
        const conversionRate = document.getElementById('conversion-rate');
        const conversionChange = document.getElementById('conversion-change');

        if (conversionRate) {
          conversionRate.textContent = data.conversion[selectedType].rate + '%';
        }
        if (conversionChange) {
          conversionChange.textContent = Math.abs(data.conversion[selectedType].change) + '%';

          // 更新趋势指示器
          const changeContainer = conversionChange.parentElement;
          const icon = changeContainer.querySelector('i');

          if (data.conversion[selectedType].trend === 'up') {
            changeContainer.className = 'd-flex align-items-center text-success small';
            icon.className = 'bi bi-arrow-up me-1';
          } else {
            changeContainer.className = 'd-flex align-items-center text-danger small';
            icon.className = 'bi bi-arrow-down me-1';
          }
        }
      }
    }
  }

  // 更新趋势指示器
  updateTrendIndicator(type, data) {
    // 查找对应的趋势指示器容器
    let container;
    if (type === 'visitor') {
      container = document.querySelector('#visitor-count').closest('.card').querySelector('.dashboard-stat-change');
    } else if (type === 'cart') {
      container = document.querySelector('#cart-count').closest('.card').querySelector('.dashboard-stat-change');
    } else if (type === 'purchase') {
      container = document.querySelector('#purchase-count').closest('.card').querySelector('.dashboard-stat-change');
    }

    if (!container) return;

    const icon = container.querySelector('i');
    const percentageSpan = container.querySelector('span:first-of-type');

    if (icon && percentageSpan) {
      // 更新百分比文本
      percentageSpan.textContent = Math.abs(data.percentage) + '%';

      // 更新趋势样式和图标
      if (data.trend === 'up') {
        container.className = 'dashboard-stat-change dashboard-change-success';
        icon.className = 'bi bi-arrow-up';
      } else {
        container.className = 'dashboard-stat-change dashboard-change-danger';
        icon.className = 'bi bi-arrow-down';
      }
    }
  }

  // 更新趋势图表
  updateTrendChart(data) {
    if (this.charts['pv-uv'] && data.labels) {
      this.charts['pv-uv'].data.labels = data.labels;
      this.charts['pv-uv'].data.datasets[0].data = data.pv;
      this.charts['pv-uv'].data.datasets[1].data = data.uv;
      this.charts['pv-uv'].update();
    }
  }

  // 更新来源图表
  updateSourceChart(data) {

    if (this.charts['source'] && data.length) {
      this.charts['source'].data.labels = data.map(item => item.name);
      // 修复：饼图应该显示百分比，而不是原始数值
      this.charts['source'].data.datasets[0].data = data.map(item => item.percentage);

      // 动态生成颜色，确保颜色与数据项数量匹配
      const colors = [
        '#165DFF',    // 蓝色
        '#0FC6C2',    // 青色
        '#00B42A',    // 绿色
        '#FF7D00',    // 橙色
        '#F53F3F',    // 红色
        '#86909C'     // 灰色
      ];

      // 根据数据项数量分配颜色
      this.charts['source'].data.datasets[0].backgroundColor = data.map((item, index) =>
        colors[index % colors.length]
      );

      this.charts['source'].update();
    } else {
    }
  }

  // 更新来源图例
  updateSourceLegend(data) {
    const legendContainer = document.getElementById('source-legend-container');
    if (!legendContainer) return;

    if (!data.length) {
      legendContainer.innerHTML = `
        <div class="text-center py-4">
          <i class="bi bi-exclamation-triangle-fill fa-2x text-muted"></i>
          <p class="text-muted mt-2">${window.dashboardTranslations.no_data}</p>
        </div>
      `;
      return;
    }

    // 定义颜色类名映射
    const colorClasses = [
      'dashboard-dot-primary', 'dashboard-dot-secondary', 'dashboard-dot-success',
      'dashboard-dot-warning', 'dashboard-dot-danger', 'dashboard-dot-dark'
    ];
    const percentClasses = [
      'dashboard-percent-primary', 'dashboard-percent-secondary', 'dashboard-percent-success',
      'dashboard-percent-warning', 'dashboard-percent-danger', 'dashboard-percent-dark'
    ];

    let html = '';
    data.forEach((item, index) => {
      const dotClass = colorClasses[index % colorClasses.length];
      const percentClass = percentClasses[index % percentClasses.length];

      html += `
        <div class="dashboard-source-item">
          <div class="dashboard-source-item-content">
            <div class="dashboard-source-dot ${dotClass}"></div>
            <span class="dashboard-source-label">${item.name}</span>
          </div>
          <div class="dashboard-source-values">
            <span class="dashboard-source-value">${item.value}</span>
            <span class="dashboard-source-percent ${percentClass}">${item.percentage}%</span>
          </div>
        </div>
      `;
    });

    legendContainer.innerHTML = html;
  }

  // 显示来源数据错误
  showSourceError(message) {
    const legendContainer = document.getElementById('source-legend-container');
    if (!legendContainer) return;

    legendContainer.innerHTML = `
      <div class="text-center py-4">
        <i class="bi bi-exclamation-triangle-fill fa-2x text-danger"></i>
        <p class="text-danger mt-2">${message}</p>
        <button class="btn btn-sm btn-outline-primary" onclick="dashboard.loadSourceData('today')">
          <i class="bi bi-arrow-repeat fs-5 me-1"></i>重试
        </button>
      </div>
    `;
  }

  // 更新漏斗显示
  updateFunnelDisplay(data) {
    if (!data.length) return;

    // 更新漏斗图表
    if (this.charts['funnel']) {
      // 使用固定的翻译标签，而不是API返回的name字段
      this.charts['funnel'].data.labels = [
        window.dashboardTranslations.product_views,
        window.dashboardTranslations.unique_visitors,
        window.dashboardTranslations.cart_additions,
        window.dashboardTranslations.orders,
        window.dashboardTranslations.successful_payments
      ];
      this.charts['funnel'].data.datasets[0].data = data.map(item => item.value);

      // 更新颜色
      this.charts['funnel'].data.datasets[0].backgroundColor = [
        '#FF7D00',  // 访问 - 橙色
        '#0FC6C2',  // 商品浏览量 - 青色
        '#00B42A',  // 加购数量 - 绿色
        '#FFB84D',  // 下单数 - 浅橙色
        '#F53F3F'   // 支付成功数 - 红色
      ];

      // 动态计算并更新X轴刻度值
      this.updateFunnelScale(data);

      this.charts['funnel'].update();
    }

    // 更新漏斗数据容器（如果需要显示详细数据）
    const funnelContainer = document.getElementById('funnel-data-container');
    if (funnelContainer) {
      let html = '<div class="funnel-details">';
      data.forEach((item, index) => {
        html += `
          <div class="funnel-item d-flex justify-content-between align-items-center mb-2">
            <span class="funnel-label">${item.name}</span>
            <div class="d-flex align-items-center">
              <div class="funnel-bar-container me-2" style="width: 100px; height: 8px; background: #f0f0f0; border-radius: 4px;">
                <div class="funnel-bar" style="height: 100%; background: ${this.charts['funnel'].data.datasets[0].backgroundColor[index]}; border-radius: 4px; width: ${item.width}%"></div>
              </div>
              <span class="funnel-value">${item.value}</span>
            </div>
          </div>
        `;
      });
      html += '</div>';
      funnelContainer.innerHTML = html;
    }
  }

  // 动态更新漏斗图表刻度值
  updateFunnelScale(data) {
    if (!this.charts['funnel'] || !data.length) return;

    // 获取数据中的最大值
    const maxValue = Math.max(...data.map(item => item.value));

    // 如果最大值为0，使用默认刻度
    if (maxValue === 0) {
      this.charts['funnel'].options.scales.x.max = 100;
      this.charts['funnel'].options.scales.x.ticks.callback = function(value) {
        return value;
      };
      return;
    }

    // 智能计算合适的最大值和刻度间隔
    let scaleMax, tickCallback;

    if (maxValue < 100) {
      // 小于100，使用10的倍数
      scaleMax = Math.ceil(maxValue / 10) * 10;
      tickCallback = function(value) {
        return value;
      };
    } else if (maxValue < 1000) {
      // 100-1000，使用50的倍数
      scaleMax = Math.ceil(maxValue / 50) * 50;
      tickCallback = function(value) {
        return value;
      };
    } else if (maxValue < 10000) {
      // 1000-10000，使用500的倍数，显示为k
      scaleMax = Math.ceil(maxValue / 500) * 500;
      tickCallback = function(value) {
        return (value / 1000).toFixed(1) + 'k';
      };
    } else if (maxValue < 100000) {
      // 10000-100000，使用5000的倍数，显示为k
      scaleMax = Math.ceil(maxValue / 5000) * 5000;
      tickCallback = function(value) {
        return (value / 1000).toFixed(0) + 'k';
      };
    } else {
      // 大于100000，使用50000的倍数，显示为万
      scaleMax = Math.ceil(maxValue / 50000) * 50000;
      tickCallback = function(value) {
        return (value / 10000).toFixed(1) + '万';
      };
    }

    // 确保最大值至少比数据最大值大20%，提供更好的视觉效果
    scaleMax = Math.max(scaleMax, Math.ceil(maxValue * 1.2));

    // 更新图表配置
    this.charts['funnel'].options.scales.x.max = scaleMax;
    this.charts['funnel'].options.scales.x.ticks.callback = tickCallback;
  }

  // 更新商品显示
  updateProductDisplay(type, data) {
    const containerId = type === 'hot' ? 'hot-products-container' : 'slow-products-container';
    const targetContainer = document.getElementById(containerId);

    if (!targetContainer) return;

    // 修复：检查 data 是否为 null 或空数组
    if (!data || !data.length) {
      targetContainer.innerHTML = `
        <div class="text-center py-4">
          <i class="bi bi-exclamation-triangle-fill fa-2x text-muted"></i>
          <p class="text-muted mt-2">${window.dashboardTranslations.no_data}</p>
        </div>
      `;
      return;
    }

    let html = '';
    data.forEach(product => {
      const iconClass = type === 'hot' ? 'bi-star-fill' : 'bi-exclamation-circle-fill';
      const iconContainerClass = type === 'hot' ? 'dashboard-icon-hot' : 'dashboard-icon-danger';
      const progressClass = type === 'hot' ? 'dashboard-progress-success' : 'dashboard-progress-danger';

      html += `
        <div class="dashboard-product-item">
          <div class="dashboard-product-icon ${iconContainerClass}">
            <i class="bi ${iconClass}"></i>
          </div>
          <div class="dashboard-product-info">
            <div class="dashboard-product-name"><a href="/products/${product.id}" title="${product.name}" target="_blank">${product.name.length > 50 ? product.name.substring(0, 50) + '...' : product.name}</a></div>
            <div class="dashboard-progress-container">
              <div class="dashboard-progress-bar">
                <div class="dashboard-progress-fill ${progressClass}" style="width: ${product.progress_width}%"></div>
              </div>
              <span class="dashboard-progress-text text-nowrap">${product.percentage}%</span>
            </div>
          </div>
          <div class="dashboard-product-price">
            <p class="dashboard-price">${product.price}</p>
            <p class="dashboard-sales">${window.dashboardTranslations.sales}: ${product.total_sold}</p>
          </div>
        </div>
      `;
    });

    targetContainer.innerHTML = html;
  }

  // 导出报表
  exportReport() {
    const timeRange = document.querySelector('.time-filter-buttons .btn-primary').textContent.trim();
    const timeRangeMap = {
      '今日': 'today',
      'Today': 'today',
      'Hoy': 'today',
      'Oggi': 'today',
      '오늘': 'today',
      'Сегодня': 'today',
      'Hari ini': 'today',
      '昨日': 'yesterday',
      'Yesterday': 'yesterday',
      'Ayer': 'yesterday',
      'Ieri': 'yesterday',
      '어제': 'yesterday',
      'Вчера': 'yesterday',
      'Kemarin': 'yesterday',
      '近7日': 'week',
      'Last 7 Days': 'week',
      'Últimos 7 días': 'week',
      'Ultimi 7 giorni': 'week',
      '최근 7일': 'week',
      'Последние 7 дней': 'week',
      '7 hari terakhir': 'week',
      '近7天': 'week'
    };

    const mappedTimeRange = timeRangeMap[timeRange] || 'today';

    // 通过 http.js 发起请求，统一登录过期处理
    const formData = new FormData();

    // 添加 CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
      formData.append('_token', csrfToken.getAttribute('content'));
    }

    // 添加参数
    formData.append('time_range', mappedTimeRange);
    formData.append('format', 'excel');

    // 显示加载状态
    const exportBtn = document.querySelector('button[onclick="bkDashboard.exportReport()"]');
    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> 导出中...';
    exportBtn.disabled = true;

    http.axiosApi.post(window.dashboardRoutes.export, formData, {
      responseType: 'blob'
    })
    .then(response => {
      // 如果响应成功，获取文件名并下载
      const contentDisposition = response.headers['content-disposition'];
      let filename = 'dashboard_report.xlsx';
      if (contentDisposition) {
        const filenameMatch = contentDisposition.match(/filename="(.+)"/);
        if (filenameMatch) {
          filename = filenameMatch[1];
        }
      }
      const url = window.URL.createObjectURL(response.data);
      const a = document.createElement('a');
      a.href = url;
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
    })
    .catch(error => {
      console.error('导出错误:', error);
      const status = error?.response?.status;
      if (status !== 401 && status !== 419) {
        alert('导出失败：网络错误');
      }
    })
    .finally(() => {
      // 恢复按钮状态
      exportBtn.innerHTML = originalText;
      exportBtn.disabled = false;
    });
  }

  // 显示加载状态
  showLoadingState() {
    // 为所有数据卡片添加加载状态
    const statCards = document.querySelectorAll('.dashboard-stat-card');
    statCards.forEach(card => {
      card.classList.add('loading');
    });

    // 为图表容器添加加载状态
    const chartContainers = document.querySelectorAll('.dashboard-chart-container');
    chartContainers.forEach(container => {
      container.classList.add('loading');
    });

    // 禁用时间切换按钮
    const timeButtons = document.querySelectorAll('.time-filter-buttons button, .dashboard-chart-buttons button');
    timeButtons.forEach(button => {
      button.disabled = true;
    });
  }

  // 隐藏加载状态
  hideLoadingState() {
    // 移除所有加载状态
    const statCards = document.querySelectorAll('.dashboard-stat-card');
    statCards.forEach(card => {
      card.classList.remove('loading');
    });

    const chartContainers = document.querySelectorAll('.dashboard-chart-container');
    chartContainers.forEach(container => {
      container.classList.remove('loading');
    });

    // 启用时间切换按钮
    const timeButtons = document.querySelectorAll('.time-filter-buttons button, .dashboard-chart-buttons button');
    timeButtons.forEach(button => {
      button.disabled = false;
    });
  }

  // 显示错误消息
  showErrorMessage(message) {
    // 创建或更新错误提示
    let errorDiv = document.getElementById('dashboard-error-message');
    if (!errorDiv) {
      errorDiv = document.createElement('div');
      errorDiv.id = 'dashboard-error-message';
      errorDiv.className = 'alert alert-danger alert-dismissible fade show';
      errorDiv.style.position = 'fixed';
      errorDiv.style.top = '20px';
      errorDiv.style.right = '20px';
      errorDiv.style.zIndex = '9999';
      errorDiv.style.minWidth = '300px';

      document.body.appendChild(errorDiv);
    }

    errorDiv.innerHTML = `
      <strong>错误:</strong> ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // 5秒后自动隐藏
    setTimeout(() => {
      if (errorDiv && errorDiv.parentNode) {
        errorDiv.parentNode.removeChild(errorDiv);
      }
    }, 5000);
  }
}

// 全局变量用于导出按钮
window.bkDashboard;

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', function() {
  const hasDashboardRoutes = typeof window.dashboardRoutes !== 'undefined';

  if (typeof Chart !== 'undefined' && hasDashboardRoutes) {
    window.bkDashboard = new EnhancedDashboard();
  }
});

// 主题切换
window.applyThemeToggle = (theme, save, checkedTheme) => {
  if (window.bkDashboard) {
    window.bkDashboard.updateChartThemes(theme);
  }
}
