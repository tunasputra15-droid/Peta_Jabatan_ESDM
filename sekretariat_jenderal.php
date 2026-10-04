        /* Struktur Organisasi Section */
        .org-section { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 25px; margin-top: 30px; margin-bottom: 30px; overflow-x: auto; }
        .org-section h4 { font-size: 12px; font-weight: 700; color: #64748B; margin-bottom: 25px; }
        
        .org-tree { display: flex; flex-direction: column; align-items: center; min-width: 1450px; padding-bottom: 15px; }
        
        .parent-node { 
            background: var(--accent-gold); 
            color: #000; 
            font-weight: 800; 
            font-size: 12.5px; 
            padding: 10px 30px; 
            border-radius: 6px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            border: 1px solid #EAB308; 
            position: relative;
            margin-bottom: 25px;
            z-index: 2; 
        }
        
          .parent-node::after {
            content: '';
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 25px;
            background-color: #D97706;
        }

        .tree-horizontal-line {
            position: relative;
            width: 91%;
            height: 2px;
            background-color: #D97706;
            margin-bottom: 20px;
        }

        .children-nodes { display: grid; grid-template-columns: repeat(10, 1fr); gap: 10px; width: 100%; z-index: 2; }
        
        .child-card-link { 
            text-decoration: none; 
            color: inherit; 
            display: block; 
            position: relative;
            padding-top: 20px; 
        }

        .child-card-link::before {
            content: '';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 50px;
            background-color: #D97706;
            z-index: 1;
        }

        .child-card { 
            background: #FFFFFF; 
            border: 2px solid var(--accent-gold); 
            border-radius: 10px; 
            padding: 16px 12px; 
            text-align: center; 
            box-shadow: 0 3px 8px rgba(0,0,0,0.06); 
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s ease-in-out;
        }

        .child-card-link:hover .child-card {
            background-color: #FEF9C3; 
            border-color: #D97706; 
            transform: translateY(-4px); 
            box-shadow: 0 8px 18px rgba(217, 119, 6, 0.18); 
        }

        .child-card .unit-name { 
            font-size: 11px; 
            font-weight: 800; 
            color: #0A192F; 
            min-height: 45px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 12px; 
            line-height: 1.3; 
        }
        
        .child-stats-box { 
            background: #F8FAFC; 
            border: 1px solid #E2E8F0; 
            border-radius: 6px; 
            padding: 8px 4px; 
            display: flex; 
            justify-content: space-around; 
            font-size: 9px; 
            font-weight: 700; 
        }
        .child-stats-box div { display: flex; flex-direction: column; align-items: center; flex: 1; border-right: 1px solid #E2E8F0; }
        .child-stats-box div:last-child { border-right: none; }
        .child-stats-box .c-label { font-size: 8px; color: #64748B; margin-bottom: 3px; }
        .child-stats-box .c-keb { color: #2563EB; font-size: 10.5px; font-weight: 800; }
        .child-stats-box .c-eks { color: #059669; font-size: 10.5px; font-weight: 800; }
        .child-stats-box .c-sel { color: #DC2626; font-size: 10.5px; font-weight: 800; }

        /* Tabel Rangkuman Eselon II */
        .table-section { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; margin-top: 20px; }
        .table-header-bar { background: var(--accent-gold); padding: 12px 20px; font-size: 12px; font-weight: 800; color: #000; }
        
        .eselon2-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .eselon2-table th, .eselon2-table td { padding: 14px 20px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .eselon2-table th { background: #F8FAFC; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; }
        .eselon2-table td { font-weight: 600; color: #334155; }
        .eselon2-table td.col-name { display: flex; align-items: center; gap: 12px; }
        .eselon2-table td.col-name i { color: #D97706; font-size: 13px; background: #FEF9C3; padding: 7px; border-radius: 6px; border: 1px solid #FDE047; }
        .eselon2-table td.num-b { color: #2563EB; text-align: right; }
        .eselon2-table td.num-e { color: #059669; text-align: right; }
        .eselon2-table td.num-s { color: #DC2626; text-align: right; font-weight: 800; }
        .eselon2-table th:nth-child(2), .eselon2-table th:nth-child(3), .eselon2-table th:nth-child(4) { text-align: right; }
        .eselon2-table tr:hover { background-color: #F8FAFC; }
    </style>
