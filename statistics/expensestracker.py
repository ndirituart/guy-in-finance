#I inted to display bar graphs of INCOME (salary, chama, money received from friends/family)
#Against EXPENSES (rent, phone bills, garbage, electricity,water, travel)
import pandas as pd
import plotly.express as px
import plotly.io as pio
import webbrowser
import os

#opening this specific file
df = pd.read_excel("Expenses.xlsx")

#Accessing the column names which I am focusing on years and money flow (IN or OUT)
print(df.head())

#Extracting only YEARS in my Date column
df['Date'] = pd.to_datetime(df['Date'], errors='coerce')

# Since data is in DD/MM/YYYY
# Extract the year from the 'Date' column and create a new 'Year' column
df['Year'] = df['Date'].dt.year

# Future plan to drop rows where 'Date' conversion failed
#For now set to FALSE because I will use it for another algorithm
df.dropna(subset=['Date'], inplace=False)

# Filter years 2020 to 2025, not necessary as my data starts and stops here
df_filtered = df[df['Year'].between(2020, 2025)]

df_grouped = df_filtered.groupby(['Year', 'Money Flow'])['Amount'].sum().reset_index()
# Plot bar chart
fig = px.bar(
    df_grouped,
    x='Year',
    y='Amount',
    color='Money Flow',
    barmode='group',
    title='Income vs Expenses (2020–2025)',
    text='Amount',
    labels={'Amount': 'Amount (in Ksh)', 'Year': 'Year'}
)

fig.update_traces(texttemplate='%{text:.2s}', textposition='outside')
fig.update_layout(
    xaxis_title="Year",
    yaxis_title="Amount in Ksh.",
    legend_title="Money Flow",
    bargap=0.3,
    template='plotly_white'
)

#pop up for the data display which is a bar graph
# --- NEW: Save to HTML and then explicitly open with webbrowser ---
chart_filename = "income_vs_expenses_chart.html"
# Construct the full path to the file
# os.path.abspath ensures you get the absolute path, which helps webbrowser find it
full_path = os.path.abspath(chart_filename)

# Save the plot to an HTML file
fig.write_html(full_path, auto_open=False) # Set auto_open to False as we'll open manually

# Open the HTML file in the default web browser
print(f"Attempting to open chart at: {full_path}")
try:
    webbrowser.open_new_tab(f"file:///{full_path}")
    print("Chart opened in default web browser.")
except Exception as e:
    print(f"Failed to open browser automatically: {e}")
    print(f"Please open this file manually in your browser: {full_path}")


fig.show()