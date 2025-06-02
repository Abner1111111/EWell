<?php
session_start();
require_once '../db_connection/database.php';
// require_once 'includes/header.php';

// Initialize variables
$income = 0;
$expenses = [];
$budgets = [];
$goals = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_income'])) {
        $amount = floatval($_POST['amount']);
        $category = 'salary';
        $date = date('Y-m-d');
        
        $sql = "INSERT INTO financial_transactions (user_id, type, amount, category, date) 
                VALUES (?, 'income', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idss", $_SESSION['user_id'], $amount, $category, $date);
        $stmt->execute();
    }
    
    if (isset($_POST['add_expense'])) {
        $amount = floatval($_POST['amount']);
        $category = $_POST['category'];
        $date = date('Y-m-d');
        
        $sql = "INSERT INTO financial_transactions (user_id, type, amount, category, date) 
                VALUES (?, 'expense', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idss", $_SESSION['user_id'], $amount, $category, $date);
        $stmt->execute();
    }
    
    if (isset($_POST['set_budget'])) {
        $category = $_POST['category'];
        $amount = floatval($_POST['amount']);
        
        $sql = "INSERT INTO budgets (user_id, category, amount) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE amount = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdd", $_SESSION['user_id'], $category, $amount, $amount);
        $stmt->execute();
    }
    
    if (isset($_POST['add_goal'])) {
        $name = $_POST['goal_name'];
        $target = floatval($_POST['target_amount']);
        $deadline = $_POST['deadline'];
        
        $sql = "INSERT INTO financial_goals (user_id, title, target_amount, deadline) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isds", $_SESSION['user_id'], $name, $target, $deadline);
        $stmt->execute();
    }

    if (isset($_POST['update_goal_progress'])) {
        $goal_id = $_POST['goal_id'];
        $current_amount = floatval($_POST['current_amount']);
        
        $sql = "UPDATE financial_goals 
                SET current_amount = ?, 
                    status = CASE 
                        WHEN ? >= target_amount THEN 'completed'
                        WHEN deadline < CURRENT_DATE THEN 'overdue'
                        ELSE 'active'
                    END
                WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddii", $current_amount, $current_amount, $goal_id, $_SESSION['user_id']);
        $stmt->execute();
    }
}

// Fetch current data
$user_id = $_SESSION['user_id'];

// Get income
$sql = "SELECT SUM(amount) as total FROM financial_transactions 
        WHERE user_id = ? AND type = 'income' AND MONTH(date) = MONTH(CURRENT_DATE())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$income = $result->fetch_assoc()['total'] ?? 0;

// Get expenses by category
$sql = "SELECT category, SUM(amount) as total FROM financial_transactions 
        WHERE user_id = ? AND type = 'expense' AND MONTH(date) = MONTH(CURRENT_DATE()) 
        GROUP BY category";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $expenses[$row['category']] = $row['total'];
}

// Get budgets
$sql = "SELECT category, amount FROM budgets WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $budgets[$row['category']] = $row['amount'];
}

// Get goals
$sql = "SELECT * FROM financial_goals WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $goals[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Planner - EWell</title>
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/budgeting.css">
   
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
</head>
<body>
    <div class="dashboard-container">
    <?php include 'includes/header.php'; ?>
        <div class="budget-container">
            <!-- Header Section -->
            <div class="budget-header">
                <h1>Financial Planner</h1>
                <div class="budget-actions">
                    <a href="export.php?type=pdf" class="btn">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="export.php?type=excel" class="btn">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <h3>Total Income</h3>
                    <div class="amount">₱<?php echo number_format($income, 2); ?></div>
                </div>
                <div class="summary-card">
                    <h3>Total Expenses</h3>
                    <div class="amount">₱<?php echo number_format(array_sum($expenses), 2); ?></div>
                </div>
                <div class="summary-card">
                    <h3>Net Balance</h3>
                    <div class="amount">₱<?php echo number_format($income - array_sum($expenses), 2); ?></div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="budget-main">
                <!-- Left Column -->
                <div class="budget-content">
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <div class="budget-card">
                            <h3><i class="fas fa-money-bill-wave"></i> Add Income</h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" id="amount" name="amount" step="0.01" required>
                                </div>
                                <button type="submit" name="add_income" class="btn">
                                    <i class="fas fa-plus"></i> Add Income
                                </button>
                            </form>
                        </div>

                        <div class="budget-card">
                            <h3><i class="fas fa-receipt"></i> Add Expense</h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="expense_amount">Amount</label>
                                    <input type="number" id="expense_amount" name="amount" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select id="category" name="category" required>
                                        <option value="food">Food</option>
                                        <option value="transportation">Transportation</option>
                                        <option value="rent">Rent</option>
                                        <option value="utilities">Utilities</option>
                                        <option value="entertainment">Entertainment</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <button type="submit" name="add_expense" class="btn">
                                    <i class="fas fa-plus"></i> Add Expense
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3><i class="fas fa-chart-pie"></i> Expense Breakdown</h3>
                            <div class="chart-container">
                                <canvas id="expenseChart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <h3><i class="fas fa-chart-bar"></i> Budget vs Actual</h3>
                            <div class="chart-container">
                                <canvas id="budgetChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="budget-sidebar">
                    <!-- Budget Setting -->
                    <div class="budget-card">
                        <h3><i class="fas fa-wallet"></i> Set Budget</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label for="budget_category">Category</label>
                                <select id="budget_category" name="category" required>
                                    <option value="food">Food</option>
                                    <option value="transportation">Transportation</option>
                                    <option value="rent">Rent</option>
                                    <option value="utilities">Utilities</option>
                                    <option value="entertainment">Entertainment</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="budget_amount">Monthly Budget</label>
                                <input type="number" id="budget_amount" name="amount" step="0.01" required>
                            </div>
                            <button type="submit" name="set_budget" class="btn">
                                <i class="fas fa-save"></i> Set Budget
                            </button>
                        </form>
                    </div>

                    <!-- Financial Goals -->
                    <div class="budget-card">
                        <h3><i class="fas fa-bullseye"></i> Financial Goals</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label for="goal_name">Goal Name</label>
                                <input type="text" id="goal_name" name="goal_name" required>
                            </div>
                            <div class="form-group">
                                <label for="target_amount">Target Amount</label>
                                <input type="number" id="target_amount" name="target_amount" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="deadline">Target Date</label>
                                <input type="date" id="deadline" name="deadline" required>
                            </div>
                            <button type="submit" name="add_goal" class="btn">
                                <i class="fas fa-plus"></i> Add Goal
                            </button>
                        </form>
                    </div>

                    <!-- Goals Progress -->
                    <div class="goals-section">
                        <div class="goals-header">
                            <h3><i class="fas fa-tasks"></i> Goals Progress</h3>
                        </div>
                        <?php if (empty($goals)): ?>
                            <p>No financial goals set yet. Add your first goal above!</p>
                        <?php else: ?>
                            <div class="goals-list">
                                <?php foreach ($goals as $goal): ?>
                                    <div class="goal-item">
                                        <h4><?php echo htmlspecialchars($goal['title'] ?? 'Unnamed Goal'); ?></h4>
                                        <p>Target: ₱<?php echo number_format($goal['target_amount'] ?? 0, 2); ?></p>
                                        <p>Current: ₱<?php echo number_format($goal['current_amount'] ?? 0, 2); ?></p>
                                        <p>Deadline: <?php echo date('F j, Y', strtotime($goal['deadline'] ?? date('Y-m-d'))); ?></p>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?php echo min(100, (($goal['current_amount'] ?? 0) / ($goal['target_amount'] ?? 1)) * 100); ?>%"></div>
                                        </div>
                                        <p class="goal-status <?php 
                                            echo $goal['status'] === 'completed' ? 'completed' : 
                                                ($goal['status'] === 'overdue' ? 'overdue' : 'in-progress'); 
                                        ?>">
                                            <?php echo ucfirst($goal['status'] ?? 'active'); ?>
                                        </p>
                                        
                                        <form method="POST" class="update-progress-form">
                                            <div class="form-group">
                                                <label for="current_amount_<?php echo $goal['id']; ?>">Update Progress</label>
                                                <input type="number" 
                                                       id="current_amount_<?php echo $goal['id']; ?>" 
                                                       name="current_amount" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="<?php echo $goal['target_amount']; ?>"
                                                       value="<?php echo $goal['current_amount'] ?? 0; ?>"
                                                       required>
                                            </div>
                                            <input type="hidden" name="goal_id" value="<?php echo $goal['id']; ?>">
                                            <button type="submit" name="update_goal_progress" class="btn">
                                                <i class="fas fa-save"></i> Update Progress
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Savings Tips -->
                    <div class="budget-card">
                        <h3><i class="fas fa-lightbulb"></i> Smart Savings Tips</h3>
                        <div class="savings-tips">
                            <?php
                            $tips = [
                                "Consider cooking at home more often to reduce food expenses.",
                                "Review your subscriptions and cancel unused services.",
                                "Set up automatic transfers to your savings account.",
                                "Use cashback cards for regular purchases.",
                                "Plan your meals and grocery shopping to avoid impulse buys."
                            ];
                            foreach ($tips as $tip) {
                                echo "<p><i class='fas fa-check-circle'></i> " . htmlspecialchars($tip) . "</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Expense Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseData = <?php echo json_encode($expenses); ?>;
        const expenseLabels = Object.keys(expenseData);
        const expenseValues = Object.values(expenseData);

        new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: expenseLabels,
                datasets: [{
                    data: expenseValues,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ₱${value.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });

        // Budget vs Actual Chart
        const budgetCtx = document.getElementById('budgetChart').getContext('2d');
        const budgetData = <?php echo json_encode($budgets); ?>;
        const categories = Object.keys(budgetData);
        const budgetValues = Object.values(budgetData);
        const actualValues = categories.map(cat => expenseData[cat] || 0);

        new Chart(budgetCtx, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [
                    {
                        label: 'Budget',
                        data: budgetValues,
                        backgroundColor: '#36A2EB',
                        borderWidth: 1
                    },
                    {
                        label: 'Actual',
                        data: actualValues,
                        backgroundColor: '#FF6384',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#333',
                            callback: function(value) {
                                return '₱' + value.toFixed(2);
                            }
                        },
                        title: {
                            display: true,
                            text: 'Amount (₱)',
                            color: '#333'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#333'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                return `${label}: ₱${value.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html> 