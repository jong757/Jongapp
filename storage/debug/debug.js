(function() {
    if (window.debugData) {
        var debugData = window.debugData;
        var uniqueKey = 'debug_' + window.location.pathname; // 使用页面路径作为唯一键

        // 创建调试窗口的 HTML
        var debugWindow = document.createElement('div');
        debugWindow.id = 'think_page_trace';
        debugWindow.style.position = 'fixed';
        debugWindow.style.bottom = '0';
        debugWindow.style.right = '0';
        debugWindow.style.width = '100%';
        debugWindow.style.zIndex = '2147483647';
        debugWindow.style.color = '#000';
        debugWindow.style.textAlign = 'left';
        debugWindow.style.fontFamily = '微软雅黑';
        debugWindow.style.fontSize = '14px';
        debugWindow.innerHTML = `
            <div id="think_page_trace_tab" style="display: none;background:white;margin:0;height: 250px;">
                <div id="think_page_trace_tab_tit" style="height:32px;padding:0px 12px; border-top:1px solid #d1d1d1; border-bottom:1px solid #d1d1d1; font-size:16px; background: #efefef;cursor: move;">
                    <span style="color:#000;padding-right:12px;height:30px;line-height:30px;display:inline-block;margin-right:3px;cursor:pointer;font-weight:700">基本</span>
                    <span style="color:#000;padding-right:12px;height:30px;line-height:30px;display:inline-block;margin-right:3px;cursor:pointer;font-weight:700">文件</span>
                    <span style="color:#000;padding-right:12px;height:30px;line-height:30px;display:inline-block;margin-right:3px;cursor:pointer;font-weight:700">错误</span>
                    <span style="color:#000;padding-right:12px;height:30px;line-height:30px;display:inline-block;margin-right:3px;cursor:pointer;font-weight:700">SQL</span>
                    <span style="color:#000;padding-right:12px;height:30px;line-height:30px;display:inline-block;margin-right:3px;cursor:pointer;font-weight:700">调试</span>
                </div>
                <div id="think_page_trace_tab_cont" style="overflow:auto;height:212px;padding:0;line-height: 24px">
                    <div style="display:none;">
                        <ol style="padding: 0; margin:0">
                            ${debugData.base.map(info => `<li style="border-bottom:1px solid #EEE;font-size:14px;padding:2px 16px">${info}</li>`).join('')}
                        </ol>
                        <p style="height:32px;"></p>
                    </div>
                    <div style="display:none;">
                        <ol style="padding: 0; margin:0">
                            ${debugData.files.map(file => `<li style="border-bottom:1px solid #EEE;font-size:14px;padding:2px 16px">${file}</li>`).join('')}
                        </ol>
                        <p style="height:32px;"></p>
                    </div>
                    <div style="display:none;">
                        <ol style="padding: 0; margin:0">
                            ${debugData.errors.map(error => `<li style="border-bottom:1px solid #EEE;font-size:14px;padding:2px 16px">${error}</li>`).join('')}
                        </ol>
                        <p style="height:32px;"></p>
                    </div>
                    <div style="display:none;">
                        <ol style="padding: 0; margin:0">
                            ${debugData.sqls.map(sql => `<li style="border-bottom:1px solid #EEE;font-size:14px;padding:2px 16px">${sql}</li>`).join('')}
                        </ol>
                        <p style="height:32px;"></p>
                    </div>
                    <div style="display:none;">
                        <ol style="padding: 0; margin:0">
                            ${debugData.trace.map(trace => `<li style="border-bottom:1px solid #EEE;font-size:14px;padding:2px 16px">${trace}</li>`).join('')}
                        </ol>
                        <p style="height:32px;"></p>
                    </div>
                </div>
            </div>
            <div id="think_page_trace_close" style="display:none;text-align:right;height:15px;position:absolute;top:10px;right:12px;cursor:pointer;">
                <b style="font-size:28px; line-height: 14px;">×</b>
            </div>
            <div id="think_page_trace_open" style="height:30px;float:right;text-align:right;overflow:hidden;position:fixed;bottom:0;right:0;z-index: 2147483647; color:#000;line-height:30px;cursor:pointer;">
                <div style="background:#232323;color:#FFF;padding:0 6px;float:right;line-height:30px;font-size:14px">${debugData.base[4]}</div>
            </div>
        `;

        document.body.appendChild(debugWindow);

        // 展开/收起功能
        var openBtn = document.getElementById('think_page_trace_open');
        var closeBtn = document.getElementById('think_page_trace_close');
        var traceTab = document.getElementById('think_page_trace_tab');
        var traceCont = document.getElementById('think_page_trace_tab_cont');

        // 事件：tab切换
        var tabTitles = document.getElementById('think_page_trace_tab_tit').getElementsByTagName('span');
        var tabContents = document.getElementById('think_page_trace_tab_cont').getElementsByTagName('div');

        // 记忆功能：恢复上次的状态
        var traceState = localStorage.getItem(uniqueKey + '_traceState');
        var activeTab = localStorage.getItem(uniqueKey + '_activeTab');
        var tabDisplayStates = JSON.parse(localStorage.getItem(uniqueKey + '_tabDisplayStates')) || {};

        if (traceState === 'open') {
            traceTab.style.display = 'block';
            closeBtn.style.display = 'block';
            openBtn.style.display = 'none';
        } else {
            traceTab.style.display = 'none';
            closeBtn.style.display = 'none';
            openBtn.style.display = 'block';
        }

        if (activeTab !== null && tabTitles[activeTab]) {
            tabTitles[activeTab].click();
        } else if (tabTitles.length > 0) {
            tabTitles[0].click();
        }

        // 恢复每个 tab 的显示状态
        for (var i = 0; i < tabContents.length; i++) {
            if (tabDisplayStates[i] === 'block') {
                tabContents[i].style.display = 'block';
                tabTitles[i].style.color = '#000';
            } else {
                tabContents[i].style.display = 'none';
                tabTitles[i].style.color = '#999';
            }
        }

        openBtn.onclick = function() {
            traceTab.style.display = 'block';
            closeBtn.style.display = 'block';
            openBtn.style.display = 'none';
            localStorage.setItem(uniqueKey + '_traceState', 'open');
        };

        closeBtn.onclick = function() {
            traceTab.style.display = 'none';
            closeBtn.style.display = 'none';
            openBtn.style.display = 'block';
            localStorage.setItem(uniqueKey + '_traceState', 'closed');
        };

        for (var i = 0; i < tabTitles.length; i++) {
            tabTitles[i].onclick = (function(i) {
                return function() {
                    for (var j = 0; j < tabContents.length; j++) {
                        tabContents[j].style.display = 'none';
                        tabTitles[j].style.color = '#999';
                        tabDisplayStates[j] = 'none';
                    }
                    tabContents[i].style.display = 'block';
                    tabTitles[i].style.color = '#000';
                    tabDisplayStates[i] = 'block';
                    localStorage.setItem(uniqueKey + '_activeTab', i);
                    localStorage.setItem(uniqueKey + '_tabDisplayStates', JSON.stringify(tabDisplayStates));
                };
            })(i);
        }

        // 增加上下拖动功能
        var titleMov = document.getElementById('think_page_trace_tab_tit');
        var isDown = false;
        var startY, startHeight;

        titleMov.onmousedown = function(e) {
            isDown = true;
            startY = e.clientY;
            startHeight = parseInt(document.defaultView.getComputedStyle(traceTab).height, 10);
            document.documentElement.style.cursor = 'move';
        };

        document.onmousemove = function(e) {
            if (!isDown) return;
            var newHeight = startHeight - (e.clientY - startY);
            traceTab.style.height = newHeight + 'px';
            traceCont.style.height = (newHeight - 38) + 'px'; // 38px 是标题栏的高度
        };

        document.onmouseup = function() {
            isDown = false;
            document.documentElement.style.cursor = 'default';
        };
    }
})();
