<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvenKopi OMORA - Inventory Dashboard</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #1c3746;
            --sidebar-text-muted: #8faab7;
            --sidebar-active-bg: #f4f8fa;
            --sidebar-active-text: #1c3746;
            --card-bg: #ffffff;
            --main-bg: #f4f8fa;
            --text-dark: #142730;
            --text-muted: #647d8c;
            --border-light: #e1ebf0;
            --accent-green-bg: #e6f6ee;
            --accent-green-text: #1b8a5a;
            --accent-coral-bg: #fdedea;
            --accent-coral-border: #f7cac3;
            --accent-coral-text: #c0392b;
            --accent-blue-bg: #e8f1f5;
            --accent-blue-text: #2a5a73;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* Full Height Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-shrink: 0;
            z-index: 20;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }

        .sidebar-top {
            padding: 24px 0 0 0;
            display: flex;
            flex-direction: column;
        }

        /* Brand Logo */
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 24px 24px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3d6a80, #142a35);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #55d697;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            flex-shrink: 0;
        }

        .brand-text h1 {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 11px;
            color: #8faab7;
            font-weight: 600;
        }

        /* Nav Links */
        .nav-list {
            list-style: none;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-left: 16px;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: var(--sidebar-text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            border-top-left-radius: 24px;
            border-bottom-left-radius: 24px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            position: relative;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.05);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            top: -20px;
            right: 0;
            width: 20px;
            height: 20px;
            background: transparent;
            border-bottom-right-radius: 20px;
            box-shadow: 6px 6px 0 6px var(--sidebar-active-bg);
            pointer-events: none;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: 0;
            width: 20px;
            height: 20px;
            background: transparent;
            border-top-right-radius: 20px;
            box-shadow: 6px -6px 0 6px var(--sidebar-active-bg);
            pointer-events: none;
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            flex-shrink: 0;
        }

        /* Sidebar Bottom Profile */
        .sidebar-bottom {
            padding: 20px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.12);
        }

        .user-profile-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-profile-left {
            display: flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4b7990, #142a35);
            border: 1.5px solid #8faab7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-info-text {
            overflow: hidden;
        }

        .user-info-text .name {
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info-text .role {
            color: #8faab7;
            font-size: 11px;
            font-weight: 500;
        }

        .logout-btn {
            background: transparent;
            border: none;
            color: #f7a399;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-btn:hover {
            color: #ff6b5b;
            background: rgba(255, 107, 91, 0.15);
        }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: var(--main-bg);
        }

        /* Top Navbar */
        .top-navbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .navbar-left h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.3px;
        }

        .navbar-left p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-pill {
            display: flex;
            align-items: center;
            background: #f6f9fb;
            border: 1px solid #d8e5ec;
            border-radius: 20px;
            padding: 7px 16px;
            width: 260px;
            gap: 8px;
            transition: all 0.2s;
        }

        .search-pill:focus-within {
            border-color: #2c6e8a;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(44, 110, 138, 0.1);
        }

        .search-pill input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 13px;
            width: 100%;
            color: var(--text-dark);
        }

        .search-pill input::placeholder {
            color: #9cb2bd;
        }

        .outlet-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #edf6fa;
            color: #1e526a;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid #d4e7f0;
        }

        .navbar-icons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b6b79;
            background: #f6f9fb;
            border: 1px solid #d8e5ec;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: #eef5f8;
            color: #1c3746;
        }

        .icon-btn.has-dot::after {
            content: '';
            position: absolute;
            top: 7px;
            right: 8px;
            width: 7px;
            height: 7px;
            background: #e74c3c;
            border-radius: 50%;
            border: 1.5px solid #ffffff;
        }

        /* Scrollable Canvas Body */
        .content-body {
            flex: 1;
            overflow-y: auto;
            padding: 28px 32px;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        /* OverView Section (4 Cards) */
        .section-header {
            font-size: 15px;
            font-weight: 700;
            color: #1c3746;
            margin-bottom: 12px;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1px solid var(--border-light);
            box-shadow: 0 2px 6px rgba(25, 52, 65, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(25, 52, 65, 0.06);
        }

        .stat-card.alert-card {
            background: #ffffff;
            border: 1.5px solid var(--accent-coral-border);
            position: relative;
        }

        .stat-card.alert-card::after {
            content: '!';
            position: absolute;
            bottom: 10px;
            right: 14px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #f7cac3;
            color: #a82e21;
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.green {
            background-color: var(--accent-green-bg);
            color: var(--accent-green-text);
        }

        .stat-icon.coral {
            background-color: var(--accent-coral-bg);
            color: var(--accent-coral-text);
        }

        .stat-info .stat-num {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.1;
        }

        .stat-info .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Widgets Grid */
        .widgets-grid {
            display: grid;
            grid-template-columns: 1fr 1.3fr 1.3fr;
            gap: 16px;
        }

        .content-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px;
            border: 1px solid var(--border-light);
            box-shadow: 0 2px 6px rgba(25, 52, 65, 0.03);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* User Shift Card */
        .user-stat-box {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f6fafb;
            border-radius: 14px;
            margin-top: 8px;
        }

        .user-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #e2eff4;
            color: #27566b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Donut Chart Simulation */
        .donut-wrap {
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap: 16px;
            padding: 8px 0;
        }

        .donut-chart {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            background: conic-gradient(#4d798c 0% 68%, #8eaab8 68% 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .donut-chart::before {
            content: '68%';
            position: absolute;
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            color: var(--sidebar-bg);
        }

        .donut-legend {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #4b6b79;
        }

        .legend-box {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        /* Bar Chart Ranking */
        .ranking-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .ranking-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 12px;
        }

        .ranking-label {
            width: 120px;
            font-weight: 600;
            color: #3b5a68;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ranking-bar-wrap {
            flex: 1;
            height: 10px;
            background: #eef4f7;
            border-radius: 6px;
            overflow: hidden;
        }

        .ranking-bar {
            height: 100%;
            background: #4d798c;
            border-radius: 6px;
        }

        .ranking-val {
            font-weight: 700;
            color: var(--sidebar-bg);
            min-width: 38px;
            text-align: right;
        }

        /* Bottom Chart Section */
        .bottom-chart-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px;
            border: 1px solid var(--border-light);
            box-shadow: 0 2px 6px rgba(25, 52, 65, 0.03);
        }

        .chart-svg-container {
            width: 100%;
            height: 150px;
            margin-top: 10px;
        }

        /* Tab Content Containers */
        .tab-content {
            display: none;
            flex-direction: column;
            gap: 20px;
        }

        .tab-content.active {
            display: flex;
        }

        /* Tables & Inputs */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        .custom-table th {
            background: #f4f8fa;
            color: #3f6070;
            font-weight: 700;
            padding: 12px 14px;
            text-align: left;
            border-bottom: 2px solid #dae7ec;
        }

        .custom-table th:first-child { border-top-left-radius: 12px; }
        .custom-table th:last-child { border-top-right-radius: 12px; }

        .custom-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e9f0f3;
            color: #243c47;
            vertical-align: middle;
        }

        .custom-table tr:hover td {
            background-color: #fbfdfd;
        }

        .type-tag {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .type-dry { background: #fdf5e6; color: #b7791f; }
        .type-frs { background: #e6fffa; color: #234e52; }
        .type-pkg { background: #ebf8ff; color: #2b6cb0; }

        /* Fast Input Controls for Barista */
        .pi-input-num {
            width: 80px;
            padding: 7px 10px;
            border: 1.5px solid #c8d9e2;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--sidebar-bg);
            text-align: right;
            outline: none;
            transition: border-color 0.2s;
        }

        .pi-input-num:focus {
            border-color: #2c6e8a;
            box-shadow: 0 0 0 3px rgba(44, 110, 138, 0.15);
        }

        .total-calculated {
            font-weight: 800;
            color: #1c6444;
            background: #edf7f1;
            padding: 6px 10px;
            border-radius: 8px;
            display: inline-block;
            min-width: 70px;
            text-align: right;
        }

        .btn-action-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 14px;
        }

        .btn-secondary {
            background: #edf3f6;
            color: #3b5c6d;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #dfe9ee;
        }

        .btn-primary {
            background: var(--sidebar-bg);
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 56, 69, 0.2);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #142a35;
            transform: translateY(-1px);
        }

        /* Filter chip row */
        .filter-row {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }

        .filter-chip {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #d8e5ec;
            background: #ffffff;
            color: #4b6a7a;
            transition: all 0.2s;
        }

        .filter-chip.active {
            background: var(--sidebar-bg);
            color: #ffffff;
            border-color: var(--sidebar-bg);
        }

        /* Toast feedback */
        .toast-msg {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--sidebar-bg);
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            z-index: 999;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            body {
                flex-direction: column;
                height: auto;
                overflow: auto;
            }
            .sidebar {
                width: 100%;
                height: auto;
            }
            .nav-list {
                flex-direction: row;
                flex-wrap: wrap;
                padding: 0 20px 16px 20px;
            }
            .nav-link.active::before,
            .nav-link.active::after {
                display: none;
            }
            .nav-link {
                border-radius: 12px;
            }
            .main-wrapper {
                height: auto;
                overflow: visible;
            }
            .overview-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .widgets-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .overview-grid {
                grid-template-columns: 1fr;
            }
            .navbar-right .search-pill {
                display: none;
            }
            .top-navbar {
                padding: 0 16px;
            }
            .content-body {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Full-Height Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-top">
            <!-- Brand Logo -->
            <div class="brand-logo-wrap">
                <div class="brand-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <h1>InvenKopi</h1>
                    <p>OMORA Coffee Shop</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <ul class="nav-list">
                <li class="nav-item">
                    <a class="nav-link active" onclick="switchTab('dashboard', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('stock-opname', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        Stock Opname (PI)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('inventory-items', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        Master Bahan (Items)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('transactions', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3 13.5 10.5"/><path d="m3 21 8.5-8.5"/></svg>
                        Transaksi Stok
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('reporting', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
                        Laporan & Variance
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Bottom User Bar -->
        <div class="sidebar-bottom">
            <div class="user-profile-bar">
                <div class="user-profile-left">
                    <div class="avatar-circle">GR</div>
                    <div class="user-info-text">
                        <div class="name">Grace</div>
                        <div class="role">Barista OMORA</div>
                    </div>
                </div>
                <button class="logout-btn" title="Keluar" onclick="alert('Keluar sistem...')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="navbar-left">
                <h2 id="pageTitle">Dashboard Overview</h2>
                <p id="pageSubtitle">Ringkasan stok bahan baku dan performa inventaris outlet</p>
            </div>

            <div class="navbar-right">
                <div class="search-pill">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7998a6" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" placeholder="Cari bahan, SKU, dokumen...">
                </div>

                <div class="outlet-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Outlet OMORA #01
                </div>

                <div class="navbar-icons">
                    <button class="icon-btn has-dot" title="2 Peringatan Stok Menipis">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Scrollable Content Canvas -->
        <main class="content-body">

            <!-- TAB 1: DASHBOARD -->
            <div id="tab-dashboard" class="tab-content active">
                <!-- OverView Section (4 Cards) -->
                <div>
                    <div class="section-header">OverView</div>
                    <div class="overview-grid">
                        <!-- Card 1 -->
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
                            </div>
                            <div class="stat-info">
                                <div class="stat-num">14</div>
                                <div class="stat-label">Total Bahan (SKU)</div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 16h6"/><path d="M19 13v6"/><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/></svg>
                            </div>
                            <div class="stat-info">
                                <div class="stat-num">28</div>
                                <div class="stat-label">Penerimaan (Bulan Ini)</div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                            </div>
                            <div class="stat-info">
                                <div class="stat-num">4,850</div>
                                <div class="stat-label">Total Stok (Unit Ecer)</div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="stat-card alert-card">
                            <div class="stat-icon coral">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                            </div>
                            <div class="stat-info">
                                <div class="stat-num">2</div>
                                <div class="stat-label">Stok Kritis (Low Stock)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Widgets Grid -->
                <div class="widgets-grid">
                    <!-- Widget 1: Petugas Aktif & Shift -->
                    <div class="content-card">
                        <div class="card-header">
                            <div class="card-title">Petugas & Shift</div>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7998a6" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                        </div>
                        <div class="user-stat-box">
                            <div class="user-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 18px; font-weight: 800; color: #142730;">Shift Pagi</div>
                                <div style="font-size: 12px; font-weight: 600; color: #6e8490; margin-top: 2px;">2 Barista Bertugas</div>
                            </div>
                        </div>
                        <div style="margin-top: 16px; font-size: 12px; color: #53717f; line-height: 1.6;">
                            • Barista: <b>Grace</b><br>
                            • Supervisor: <b>Kira</b><br>
                            • Status Form PI: <span style="color: #c0392b; font-weight: 700;">Belum Submit Hari Ini</span>
                        </div>
                    </div>

                    <!-- Widget 2: Donut Chart Inventory Values -->
                    <div class="content-card">
                        <div class="card-header">
                            <div class="card-title">Inventory Values</div>
                        </div>
                        <div class="donut-wrap">
                            <div class="donut-chart"></div>
                            <div class="donut-legend">
                                <div class="legend-row">
                                    <div class="legend-box" style="background-color: #8eaab8;"></div>
                                    <span>Bahan Terpakai (32%)</span>
                                </div>
                                <div class="legend-row">
                                    <div class="legend-box" style="background-color: #4d798c;"></div>
                                    <span>Sisa Stok (68%)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 3: Top Bahan Paling Banyak Dipakai -->
                    <div class="content-card">
                        <div class="card-header">
                            <div class="card-title">Top 5 Pemakaian Bahan</div>
                        </div>
                        <div class="ranking-list">
                            <div class="ranking-item">
                                <span class="ranking-label">Fresh Milk</span>
                                <div class="ranking-bar-wrap"><div class="ranking-bar" style="width: 88%;"></div></div>
                                <span class="ranking-val">8.8 L</span>
                            </div>
                            <div class="ranking-item">
                                <span class="ranking-label">Biji Kopi Arabika</span>
                                <div class="ranking-bar-wrap"><div class="ranking-bar" style="width: 72%;"></div></div>
                                <span class="ranking-val">3.2 Kg</span>
                            </div>
                            <div class="ranking-item">
                                <span class="ranking-label">Sirup Vanila</span>
                                <div class="ranking-bar-wrap"><div class="ranking-bar" style="width: 55%;"></div></div>
                                <span class="ranking-val">1.4 L</span>
                            </div>
                            <div class="ranking-item">
                                <span class="ranking-label">Cup 16oz OMORA</span>
                                <div class="ranking-bar-wrap"><div class="ranking-bar" style="width: 48%;"></div></div>
                                <span class="ranking-val">96 Pcs</span>
                            </div>
                            <div class="ranking-item">
                                <span class="ranking-label">Gula Aren Cair</span>
                                <div class="ranking-bar-wrap"><div class="ranking-bar" style="width: 35%;"></div></div>
                                <span class="ranking-val">850 ml</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Chart: Tren Pemakaian vs Penjualan -->
                <div class="bottom-chart-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Tren Pemakaian Bahan vs Penjualan Menu</div>
                            <span style="font-size: 11px; color: #8faab7; font-weight: 600;">Data Periode 6 Bulan Terakhir</span>
                        </div>
                        <div style="font-size: 12px; font-weight: 700; color: #27ae60; background: #e8f8f0; padding: 4px 10px; border-radius: 8px;">
                            Efisiensi Stok 94.2%
                        </div>
                    </div>

                    <div class="chart-svg-container">
                        <svg viewBox="0 0 800 130" width="100%" height="100%" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#4d798c" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#4d798c" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <line x1="0" y1="30" x2="800" y2="30" stroke="#f0f5f7" stroke-width="1"/>
                            <line x1="0" y1="70" x2="800" y2="70" stroke="#f0f5f7" stroke-width="1"/>
                            <line x1="0" y1="110" x2="800" y2="110" stroke="#f0f5f7" stroke-width="1"/>
                            <path d="M 0,90 Q 150,40 300,75 T 600,35 T 800,45 L 800,120 L 0,120 Z" fill="url(#chartGrad)"/>
                            <path d="M 0,90 Q 150,40 300,75 T 600,35 T 800,45" fill="none" stroke="#2c5a70" stroke-width="3"/>
                            <circle cx="300" cy="75" r="5" fill="#f39c12"/>
                            <circle cx="600" cy="35" r="5" fill="#27ae60"/>
                        </svg>
                        <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; color: #8faab7; margin-top: 4px;">
                            <span>Jun</span>
                            <span>Jul</span>
                            <span>Agu</span>
                            <span>Sep</span>
                            <span>Okt</span>
                            <span>Nov</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: STOCK OPNAME (FORM PI) -->
            <div id="tab-stock-opname" class="tab-content">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title" style="font-size: 18px;">Form Perhitungan Stok Fisik (Physical Inventory)</div>
                            <span style="font-size: 12px; color: var(--text-muted);">
                                Modul M09 — Ringkas & ergonomis untuk barista saat jam ramai gerai (Revisi D1-05).
                            </span>
                        </div>
                        <div style="background: #e8f3f8; color: #1d556e; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                            Tanggal: {{ date('d M Y') }}
                        </div>
                    </div>

                    <!-- Quick Filter Barista -->
                    <div class="filter-row">
                        <span class="filter-chip active" onclick="filterPiTable('ALL', this)">Semua Kategori</span>
                        <span class="filter-chip" onclick="filterPiTable('DRY', this)">Bahan Kering (Biji Kopi/Bubuk)</span>
                        <span class="filter-chip" onclick="filterPiTable('FRS', this)">Bahan Segar (Susu/Sirup)</span>
                        <span class="filter-chip" onclick="filterPiTable('PKG', this)">Packaging (Cup/Sedotan)</span>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table" id="piTable">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Nama Bahan</th>
                                    <th>Tipe</th>
                                    <th>Satuan Kemasan</th>
                                    <th style="text-align: right;">Sisa Kemasan (Utuh)</th>
                                    <th style="text-align: right;">Sisa Ecer</th>
                                    <th style="text-align: right;">Total Fisik (Ecer)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-category="DRY">
                                    <td><b>DRY-01</b></td>
                                    <td>Biji Kopi Arabika House Blend</td>
                                    <td><span class="type-tag type-dry">DRY</span></td>
                                    <td>Pouch (1.000 g)</td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="4" data-perkemasan="1000" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="350" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="total-calculated">4.350 g</span>
                                    </td>
                                </tr>
                                <tr data-category="FRS">
                                    <td><b>FRS-01</b></td>
                                    <td>Fresh Milk Pasteurisasi</td>
                                    <td><span class="type-tag type-frs">FRS</span></td>
                                    <td>Karton (1.000 ml)</td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="8" data-perkemasan="1000" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="450" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="total-calculated">8.450 ml</span>
                                    </td>
                                </tr>
                                <tr data-category="FRS">
                                    <td><b>FRS-02</b></td>
                                    <td>Sirup Vanila OMORA</td>
                                    <td><span class="type-tag type-frs">FRS</span></td>
                                    <td>Botol (750 ml)</td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="2" data-perkemasan="750" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="200" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="total-calculated">1.700 ml</span>
                                    </td>
                                </tr>
                                <tr data-category="DRY">
                                    <td><b>DRY-02</b></td>
                                    <td>Bubuk Matcha Premium</td>
                                    <td><span class="type-tag type-dry">DRY</span></td>
                                    <td>Pack (500 g)</td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="1" data-perkemasan="500" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="120" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="total-calculated">620 g</span>
                                    </td>
                                </tr>
                                <tr data-category="PKG">
                                    <td><b>PKG-01</b></td>
                                    <td>Cup Plastik 16oz OMORA</td>
                                    <td><span class="type-tag type-pkg">PKG</span></td>
                                    <td>Slop (50 pcs)</td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="6" data-perkemasan="50" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <input type="number" class="pi-input-num" value="14" oninput="calculateRow(this)">
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="total-calculated">314 pcs</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="btn-action-group">
                        <button class="btn-secondary" onclick="showToast('Draft Stock Opname berhasil disimpan!')">
                            Simpan Draft Sementara
                        </button>
                        <button class="btn-primary" onclick="showToast('Stock Opname berhasil disubmit ke Supervisor!')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Submit Hasil Stock Opname
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB 3: MASTER BAHAN (ITEMS) -->
            <div id="tab-inventory-items" class="tab-content">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title" style="font-size: 18px;">Master Data Bahan & Kemasan (SKU)</div>
                            <span style="font-size: 12px; color: var(--text-muted);">
                                Modul M02 — Mengacu pada kontrak API endpoint <code>/api/v1/items</code>
                            </span>
                        </div>
                        <button class="btn-primary" style="padding: 6px 14px; font-size: 12px;" onclick="showToast('Fitur Tambah Item Baru')">
                            + Tambah Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Nama Bahan Baku</th>
                                    <th>Tipe</th>
                                    <th>Satuan Ecer</th>
                                    <th>Kemasan Utuh</th>
                                    <th>Isi / Kemasan</th>
                                    <th>Min. Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>DRY-01</b></td>
                                    <td>Biji Kopi Arabika House Blend</td>
                                    <td><span class="type-tag type-dry">DRY</span></td>
                                    <td>Gram (g)</td>
                                    <td>Pouch</td>
                                    <td>1.000 g</td>
                                    <td>2.000 g</td>
                                    <td><span style="color: #27ae60; font-weight: 700;">Aman</span></td>
                                </tr>
                                <tr>
                                    <td><b>FRS-01</b></td>
                                    <td>Fresh Milk Pasteurisasi</td>
                                    <td><span class="type-tag type-frs">FRS</span></td>
                                    <td>Mililiter (ml)</td>
                                    <td>Karton</td>
                                    <td>1.000 ml</td>
                                    <td>5.000 ml</td>
                                    <td><span style="color: #27ae60; font-weight: 700;">Aman</span></td>
                                </tr>
                                <tr>
                                    <td><b>FRS-02</b></td>
                                    <td>Sirup Vanila OMORA</td>
                                    <td><span class="type-tag type-frs">FRS</span></td>
                                    <td>Mililiter (ml)</td>
                                    <td>Botol</td>
                                    <td>750 ml</td>
                                    <td>1.500 ml</td>
                                    <td><span style="color: #c0392b; font-weight: 700;">Menipis</span></td>
                                </tr>
                                <tr>
                                    <td><b>PKG-01</b></td>
                                    <td>Cup Plastik 16oz OMORA</td>
                                    <td><span class="type-tag type-pkg">PKG</span></td>
                                    <td>Pcs</td>
                                    <td>Slop</td>
                                    <td>50 pcs</td>
                                    <td>100 pcs</td>
                                    <td><span style="color: #27ae60; font-weight: 700;">Aman</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: TRANSAKSI STOK -->
            <div id="tab-transactions" class="tab-content">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title" style="font-size: 18px;">Riwayat Transaksi Stok Masuk & Keluar</div>
                            <span style="font-size: 12px; color: var(--text-muted);">
                                Modul M05-M08 (Penerimaan, Transfer, Waste, Penjualan)
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Dokumen</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Bahan / SKU</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan / Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('d/m/Y') }}</td>
                                    <td><b>RCV-202611-008</b></td>
                                    <td><span class="type-tag type-frs">Penerimaan (Receipt)</span></td>
                                    <td>Fresh Milk Pasteurisasi</td>
                                    <td style="font-weight: 700; color: #1c6444;">+12 Karton</td>
                                    <td>Supplier Susu Segar Sejahtera</td>
                                </tr>
                                <tr>
                                    <td>{{ date('d/m/Y') }}</td>
                                    <td><b>WST-202611-002</b></td>
                                    <td><span class="type-tag type-dry">Waste (Terbuang)</span></td>
                                    <td>Biji Kopi Arabika</td>
                                    <td style="font-weight: 700; color: #c0392b;">-180 g</td>
                                    <td>Kalibrasi Grinder Pagi</td>
                                </tr>
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime('-1 day')) }}</td>
                                    <td><b>TRF-202611-001</b></td>
                                    <td><span class="type-tag type-pkg">Transfer Out</span></td>
                                    <td>Cup Plastik 16oz</td>
                                    <td style="font-weight: 700; color: #2b6cb0;">-50 pcs</td>
                                    <td>Transfer ke Booth Event Kampus</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: LAPORAN & VARIANCE -->
            <div id="tab-reporting" class="tab-content">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title" style="font-size: 18px;">Laporan Variance & Rekomendasi Order</div>
                            <span style="font-size: 12px; color: var(--text-muted);">
                                Modul M10 & M11 — Analisis Selisih Hitung Fisik vs Catatan Sistem
                            </span>
                        </div>
                        <button class="btn-secondary" onclick="window.print()">Cetak Laporan</button>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Bahan Baku</th>
                                    <th>Stok Awal</th>
                                    <th>Masuk</th>
                                    <th>Usage (BOM)</th>
                                    <th>Std. End Stock</th>
                                    <th>PI Fisik</th>
                                    <th>Selisih (Variance)</th>
                                    <th>Status Klasifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>Biji Kopi Arabika</b></td>
                                    <td>5.000 g</td>
                                    <td>+2.000 g</td>
                                    <td>-2.500 g</td>
                                    <td>4.500 g</td>
                                    <td>4.350 g</td>
                                    <td style="font-weight: 700; color: #c0392b;">-150 g (-3.3%)</td>
                                    <td><span style="color: #27ae60; font-weight: 700;">Normal (Toleransi)</span></td>
                                </tr>
                                <tr>
                                    <td><b>Fresh Milk</b></td>
                                    <td>6.000 ml</td>
                                    <td>+12.000 ml</td>
                                    <td>-9.500 ml</td>
                                    <td>8.500 ml</td>
                                    <td>8.450 ml</td>
                                    <td style="font-weight: 700; color: #c0392b;">-50 ml (-0.6%)</td>
                                    <td><span style="color: #27ae60; font-weight: 700;">Normal</span></td>
                                </tr>
                                <tr>
                                    <td><b>Sirup Vanila</b></td>
                                    <td>3.000 ml</td>
                                    <td>0 ml</td>
                                    <td>-900 ml</td>
                                    <td>2.100 ml</td>
                                    <td>1.700 ml</td>
                                    <td style="font-weight: 700; color: #c0392b;">-400 ml (-19.0%)</td>
                                    <td><span style="color: #c0392b; font-weight: 800;">High Variance ⚠️</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast-msg">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#55d697" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toastText">Berhasil!</span>
    </div>

    <!-- Client-side Fast Logic -->
    <script>
        const titles = {
            'dashboard': { title: 'Dashboard Overview', subtitle: 'Ringkasan stok bahan baku dan performa inventaris outlet' },
            'stock-opname': { title: 'Stock Opname (Physical Inventory)', subtitle: 'Pencatatan hitung stok fisik bahan baku dan kemasan harian' },
            'inventory-items': { title: 'Master Bahan & Kemasan (SKU)', subtitle: 'Katalog bahan baku, spesifikasi konversi satuan utuh/ecer, dan stok minimum' },
            'transactions': { title: 'Riwayat Transaksi Stok', subtitle: 'Pencatatan penerimaan barang, mutasi transfer, dan waste harian' },
            'reporting': { title: 'Laporan & Variance Stok', subtitle: 'Analisis selisih persediaan fisik terhadap catatan sistem dan rekomendasi order' }
        };

        // Tab Switcher
        function switchTab(tabId, element) {
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            element.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            const activeTab = document.getElementById('tab-' + tabId);
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Update Navbar Title
            if (titles[tabId]) {
                document.getElementById('pageTitle').innerText = titles[tabId].title;
                document.getElementById('pageSubtitle').innerText = titles[tabId].subtitle;
            }
        }

        // Fast Realtime Calculation for Form PI (M09 formula)
        function calculateRow(inputElem) {
            const row = inputElem.closest('tr');
            const inputs = row.querySelectorAll('.pi-input-num');
            const utuhInput = inputs[0];
            const ecerInput = inputs[1];
            const totalSpan = row.querySelector('.total-calculated');

            const perKemasan = parseFloat(utuhInput.dataset.perkemasan) || 1;
            const utuh = parseFloat(utuhInput.value) || 0;
            const ecer = parseFloat(ecerInput.value) || 0;

            const total = (utuh * perKemasan) + ecer;
            
            const unit = totalSpan.innerText.replace(/[0-9.,\s]/g, '');
            totalSpan.innerText = total.toLocaleString('id-ID') + ' ' + unit;
        }

        // Filter Table Category
        function filterPiTable(category, chip) {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');

            const rows = document.querySelectorAll('#piTable tbody tr');
            rows.forEach(row => {
                if (category === 'ALL' || row.dataset.category === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Toast feedback
        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toastText').innerText = message;
            toast.style.display = 'flex';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
