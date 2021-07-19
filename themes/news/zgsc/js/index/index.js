
$(function () {
    Cms.siteFlow("", location.href, document.referrer, "true");
    $.cookie("_site_id_cookie", "17", {
        path: ""
    });
})

// -----------------------------------------------------

// 获取echarts的容器
var chart = echarts.init(document.getElementById("main"));

function world() {
    /*
        图中相关城市经纬度,根据你的需求添加数据
        关于国家的经纬度，可以用首都的经纬度或者其他城市的经纬度
    */
    var geoCoordMap = {};
    /* 
        记录下起点城市和终点城市的名称，以及权重
        数组第一位为终点城市，数组第二位为起点城市，以及该城市的权重，就是图上圆圈的大小
     */

    // 北京
    var BJData = [];

    var color = ['#9ae5fc', '#dcbf71']; // 自定义图中要用到的颜色
    var series = []; // 用来存储地图数据
    [
        ['北京', BJData],
    ].forEach(function (item, i) {
        series.push({ // 散点效果
            type: 'effectScatter',
            coordinateSystem: 'geo', // 表示使用的坐标系为地理坐标系
            zlevel: 3,
            rippleEffect: {
                brushType: 'stroke' // 波纹绘制效果
            },
            label: {
                normal: { // 默认的文本标签显示样式
                    show: false,
                    position: 'left', // 标签显示的位置
                    formatter: '{b}' // 标签内容格式器
                }
            },
            itemStyle: {
                normal: {
                    color: '#005bac',
                }
            },
        });
    });

    // 显示终点位置,类似于上面最后一个效果，放在外面写，是为了防止被循环执行多次
    series.push({
        type: 'effectScatter',
        coordinateSystem: 'geo',
        zlevel: 3,
        rippleEffect: {
            brushType: 'stroke'
        },
        label: {
            normal: {
                show: false,
                fontSize: 14,
                position: 'left',
                formatter: '{b}'
            }
        },
        symbolSize: function (val) {
            return val[2] / 8;
        },
        itemStyle: {
            normal: {
                color: "#00dcff" //散点颜色
            },
            emphasis: {
                color: "#fff", //鼠标悬浮散点颜色
            }
        },
        // 
        data: [{
            name: '拉斯',
            value: [-16.5474373, 28.9202202, 80],
            com: [""]
        }, {
            name: '马德里',
            value: [-3.45, 40.25, 80],
            com: [""]
        }, {
            name: '葡萄牙',
            value: [-11.5, 38.42, 80],
            com: [""]
        }, {
            name: '也门',
            value: [44.7943804, 12.8257481, 80],
            com: [""]
        }, {
            name: '阿曼',
            value: [58.37, 23.36, 80],
            com: [""]
        }, {
            name: '缅甸',
            value: [96.1, 19.45, 80],
            com: [""]
        }, {
            name: '印度尼西亚',
            value: [112.504416, 0.123155, 80],
            com: [""]
        }, {
            name: '摩洛哥',
            value: [-7.37, 33.36, 80],
            com: [""]
        }, {
            name: '毛里塔尼亚',
            value: [-9.940835, 30.00789, 80],
            com: [""]
        }, {
            name: '塞内加尔',
            value: [-14.18, 24.89, 80],
            com: [""]
        }, {
            name: '几内亚比绍',
            value: [-15.35, 20.52, 80],
            com: [""]
        }, {
            name: '几内亚',
            value: [-15.43, 16.31, 80],
            com: [""]
        }, {
            name: '塞拉里昂',
            value: [-14.14, 11.80, 80],
            com: [""]
        }, {
            name: '加纳',
            value: [1.15, 8.33, 80],
            com: [""]
        }, {
            name: '莫桑比克',
            value: [36.35, -16.58, 80],
            com: [""]
        }, {
            name: '马达加斯加',
            value: [46.32, -19.55, 80],
            com: [""]
        }, {
            name: '厄瓜多尔',
            value: [-79.27, 0.00, 80],
            com: [""]
        }, {
            name: '苏里南',
            value: [-56.49, 5.51, 80],
            com: [""]
        }, {
            name: '委内瑞拉',
            value: [-66.58, 10.3, 80],
            com: [""]
        }]
    });

    // 最后初始化世界地图中的相关数据
    chart.clear();
    chart.setOption({
        tooltip: {
            formatter: function (params) {
                // 悬浮地点显示公司信息(若不需可注释)
                if (params.componentSubType == "effectScatter") {
                    var info = '<p style="font-size:14px;">' + params.name +
                        '</p>'
                    return info;
                }
                // if (params.componentSubType == "effectScatter") {
                // 	var info = '<p style="font-size:14px;">' + params.name +
                // 		'：</p><p style="font-size:12px;padding-left:10px;padding-right:10px;">' + params.data.com + '</p>'
                // 	return info;
                // }
            },
            backgroundColor: "#00387c", //提示标签背景颜色 
            textStyle: {
                color: "#fff"
            } //提示标签字体颜色 
        },
        toolbox: { //工具栏
            show: false,
            orient: 'vertical', //工具栏 icon 的布局朝向
            x: 'right',
            y: 'center',
            feature: { //各工具配置项。
                mark: {
                    show: true
                },
                dataView: {
                    show: true,
                    readOnly: false
                }, //数据视图工具，可以展现当前图表所用的数据，编辑后可以动态更新。
                restore: {
                    show: true
                }, //配置项还原。
                saveAsImage: {
                    show: true
                } //保存为图片。
            }
        },
        geo: {
            map: 'world', // 与引用进来的地图js名字一致
            roam: false, // 禁止缩放平移
            itemStyle: { // 每个区域的样式 
                normal: {
                    areaColor: 'none', //设置地图背景色
                    borderColor: '#fff',
                },
                emphasis: {
                    areaColor: 'none',
                }
            },
            label: { // 高亮的时候不显示标签
                emphasis: {
                    show: false,
                }
            },
        },
        series: series, // 将之前处理的数据放到这里
        textStyle: {
            fontSize: 10
        }
    });
}
chart.clear();
world();

//--------------------------------------------------

var x = 0,
    y = 0;
var xin = true,
    yin = true;
var step = 1;
var delay = 50;
var obj = document.getElementById("float");

function closediv() {
    obj.style.visibility = "hidden";
}

function float() {
    var L = T = 0;
    var R = document.documentElement.clientWidth - obj.offsetWidth;
    var B = document.documentElement.clientHeight - obj.offsetHeight;
    obj.style.left = x + document.documentElement.scrollLeft + "px";
    obj.style.top = y + document.documentElement.scrollTop + "px";
    x = x + step * (xin ? 1 : -1);
    if (x < L) {
        xin = true;
        x = L;
    }
    if (x > R) {
        xin = false;
        x = R;
    }
    y = y + step * (yin ? 1 : -1);
    if (y < T) {
        yin = true;
        y = T;
    }
    if (y > B) {
        yin = false;
        y = B;
    }
}
var itl = setInterval("float()", delay);
obj.onmouseover = function () {
    clearInterval(itl);
}
obj.onmouseout = function () {
    itl = setInterval("float()", delay);
}