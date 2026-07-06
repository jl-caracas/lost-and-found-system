<?php
/**
 * views/reports/print.php – Printable Reports View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found System - Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body { 
            font-family: 'Inter', Arial, sans-serif; 
            margin: 40px; 
            color: #2c3e50; 
            background-color: #f8f9fa;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header { 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px; 
            border-bottom: 3px solid #3498db; 
            padding-bottom: 20px; 
        }
        .header img {
            max-height: 80px;
            margin-right: 20px;
        }
        .header-text {
            text-align: left;
        }
        .header h1 { 
            margin: 0 0 5px 0; 
            font-size: 28px; 
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p { 
            margin: 0; 
            color: #7f8c8d; 
            font-size: 16px;
            font-weight: 600;
        }
        .filters-summary { 
            margin-bottom: 25px; 
            font-size: 14px; 
            background: #eaf2f8; 
            padding: 15px 20px; 
            border-left: 4px solid #3498db;
            border-radius: 4px;
            color: #34495e;
        }
        .filters-summary strong { 
            margin-right: 15px; 
            color: #2980b9;
        }
        table { 
            border-collapse: collapse; 
            margin-top: 20px; 
            width: 100%; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        th, td { 
            border: 1px solid #ecf0f1; 
            padding: 12px 15px; 
            text-align: left; 
            font-size: 14px; 
        }
        th { 
            background-color: #34495e; 
            color: #ffffff;
            font-weight: 600; 
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) { background-color: #fbfcfc; }
        tr:hover { background-color: #f0f3f4; }
        .footer { 
            margin-top: 50px; 
            text-align: center; 
            font-size: 12px; 
            color: #95a5a6; 
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
        }
        @media print {
            body { 
                margin: 0; 
                padding: 0; 
                background: #fff;
            }
            .report-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            button { display: none; }
            .filters-summary {
                background: #fff;
                border: 1px solid #ddd;
                border-left: 4px solid #3498db;
            }
        }
    </style>
</head>
<body onload="window.print()">
<div class="report-container">

    <div class="header">
        <img src="assets/logo.png" alt="System Logo">
        <div class="header-text">
            <h1>Lost and Found Tracking System</h1>
            <p>Official Items Report</p>
        </div>
    </div>

    <div class="filters-summary">
        <strong>Filters Applied:</strong><br>
        <span style="display:inline-block; margin-top:5px;">
            <b>Status:</b> <?php echo empty($status) ? 'All' : ucfirst(htmlspecialchars($status)); ?> &nbsp;|&nbsp; 
            <b>Category:</b> <?php echo htmlspecialchars($category_name); ?> &nbsp;|&nbsp; 
            <b>Date Range:</b> <?php echo empty($start_date) ? 'Beginning' : htmlspecialchars($start_date); ?> to <?php echo empty($end_date) ? 'Present' : htmlspecialchars($end_date); ?>
        </span>
    </div>

    <?php if(mysqli_num_rows($items) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Label</th>
                    <th>Location</th>
                    <th>Date Reported</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo strtoupper($row['status']); ?></td>
                        <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $row['status_label']))); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo date('M d, Y, g:i A', strtotime($row['date_reported'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; padding: 50px; color: #666;">No items found matching the selected criteria.</p>
    <?php endif; ?>

    <div class="footer">
        Generated on <?php echo date('F j, Y, g:i a'); ?> by <?php echo htmlspecialchars($_SESSION['username']); ?>
    </div>

</div> <!-- end of .report-container -->
</body>
</html>

