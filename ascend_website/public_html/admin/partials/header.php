<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ASCB CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-collapsed-width: 64px;
            --sidebar-bg: #0B2F6B;
            --sidebar-hover: #1F4E9C;
            --topbar-height: 60px;
            --gold: #F2A900;
            --transition: 0.28s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f4f9;
            margin: 0;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: width var(--transition);
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Logo area */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            min-height: var(--topbar-height);
            overflow: hidden;
            white-space: nowrap;
        }
        .sidebar-brand img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-text {
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.2;
            transition: opacity var(--transition);
        }
        .sidebar.collapsed .brand-text { opacity: 0; pointer-events: none; }

        /* Nav links */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        .nav-section-label {
            color: rgba(255,255,255,0.35);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 16px 18px 6px;
            white-space: nowrap;
            transition: opacity var(--transition);
        }
        .sidebar.collapsed .nav-section-label { opacity: 0; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: 0.88rem;
            font-weight: 500;
            white-space: nowrap;
            transition: background var(--transition), color var(--transition);
            position: relative;
        }
        .sidebar-nav a svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            stroke: currentColor;
        }
        .sidebar-nav a .link-label {
            transition: opacity var(--transition);
        }
        .sidebar.collapsed .sidebar-nav a .link-label { opacity: 0; }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: var(--sidebar-hover);
            color: white;
        }
        .sidebar-nav a.active {
            background: linear-gradient(90deg, #1F4E9C, #2660bb);
            border-left: 3px solid var(--gold);
        }

        /* Tooltip for collapsed state */
        .sidebar.collapsed .sidebar-nav a::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-collapsed-width) + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s;
            z-index: 9999;
        }
        .sidebar.collapsed .sidebar-nav a:hover::after { opacity: 1; }

        /* Footer / logout */
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 10px 0;
            overflow: hidden;
        }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e5e9f0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 999;
            transition: left var(--transition);
            gap: 12px;
        }
        .topbar.collapsed { left: var(--sidebar-collapsed-width); }

        /* Toggle button */
        #sidebarToggle {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, color 0.2s;
        }
        #sidebarToggle:hover { background: #f1f4f9; color: #0B2F6B; }

        .topbar-title {
            font-weight: 600;
            font-size: 1rem;
            color: #1e293b;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 16px;
            color: #64748b;
            font-size: 0.88rem;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 28px;
            transition: margin-left var(--transition);
            min-height: 100vh;
        }
        .main-content.collapsed { margin-left: var(--sidebar-collapsed-width); }

        /* Stat cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border-left: 4px solid var(--gold);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
        .stat-card .stat-label { color: #64748b; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .stat-value { font-size: 2.2rem; font-weight: 800; color: #1e293b; line-height: 1; margin-top: 6px; }

        .badge-dot {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: #ef4444;
            color: white;
            border-radius: 99px;
            font-size: 0.7rem;
            padding: 1px 7px;
            font-weight: 700;
        }
        .sidebar.collapsed .badge-dot { right: 6px; top: 6px; transform: none; font-size: 0.6rem; padding: 1px 4px; }
    </style>
</head>
<body>
