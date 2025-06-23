#This is a separate file to look at savings habits
#The last one showed differences between INCOME and EXPENSES
#This one shows the ups and downs and by this we can predict high cost seasons and low cost ones
# This helps to know how to survive dry and heavy money seasons especially if illness happens

import pandas as pd
import plotly.express as px
import plotly.io as pio
import webbrowser
import os

# opening this specific file
df = pd.read_excel("Expenses.xlsx")

# Ensure the 'Date' column is in datetime format
df['Date'] = pd.to_datetime(df['Date'], errors='coerce')

# Drop rows where 'Date' conversion failed, as these rows won't have valid dates for analysis
df.dropna(subset=['Date'], inplace=True)

# Filter years from 2020 to 2025
df_filtered = df[df['Date'].dt.year.between(2020, 2025)].copy() # Use .copy() to avoid SettingWithCopyWarning

# Create 'MonthYear' column for consistent grouping
df_filtered['MonthYear'] = df_filtered['Date'].dt.to_period('M').astype(str)

# Group by MonthYear and Money Flow, then sum the 'Amount'
monthly_summary = df_filtered.groupby(['MonthYear', 'Money Flow'])['Amount'].sum().unstack(fill_value=0)

# Calculate monthly Net Flow (Income - Expenses)
monthly_summary['Net Flow'] = monthly_summary['IN'] - monthly_summary['OUT']

# Convert 'MonthYear' back to datetime for proper sorting and plotting
monthly_summary.index = pd.to_datetime(monthly_summary.index)
monthly_summary = monthly_summary.sort_index()

# Calculate Cumulative Net Flow
monthly_summary['Cumulative Net Flow'] = monthly_summary['Net Flow'].cumsum()

# Reset index to make 'MonthYear' a column for Plotly Express
monthly_summary = monthly_summary.reset_index()
monthly_summary = monthly_summary.rename(columns={'index': 'MonthYear'}) # Rename the index column back

# --- Plot the Cumulative Net Flow Line Chart ---
fig = px.line(
    monthly_summary,
    x='MonthYear',
    y='Cumulative Net Flow',
    title='Cumulative Net Income (Savings/Loss) Over Time (2020-2025)',
    labels={
        'MonthYear': 'Date (Month-Year)',
        'Cumulative Net Flow': 'Cumulative Net Income ($)'
    },
    hover_data={
        'MonthYear': '|%Y-%m', # Format month and year for hover
        'IN': ':.2f',          # Show monthly IN on hover
        'OUT': ':.2f',         # Show monthly OUT on hover
        'Net Flow': ':.2f',    # Show monthly Net Flow on hover
        'Cumulative Net Flow': ':.2f', # Show cumulative on hover
    }
)

# Customize the line color
fig.update_traces(line_color='darkgreen', mode='lines+markers') # Add markers to easily tap points

fig.update_layout(
    xaxis_title="Month-Year",
    yaxis_title="Cumulative Net Income ($)",
    hovermode="x unified", # Shows all data for a given x-axis point
    template='plotly_white'
)

# --- Browser Pop-up Code ---
chart_filename = "cumulative_net_income_chart.html"
full_path = os.path.abspath(chart_filename)
fig.write_html(full_path, auto_open=False)
print(f"Attempting to open chart at: {full_path}")
try:
    webbrowser.open_new_tab(f"file:///{full_path}")
    print("Chart opened in default web browser.")
except Exception as e:
    print(f"Failed to open browser automatically: {e}")
    print(f"Please open this file manually in your browser: {full_path}")