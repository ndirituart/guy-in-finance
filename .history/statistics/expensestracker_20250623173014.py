#so as a start I just want bar graphs of INCOME (salary, chama, money received from friends/family)
#Agains EXPENSES (rent, phone bills, garbage, electricity,water, travel)

import pandas as pd
import pandas as pd
import plotly.express as pd
import plotly.express as px

#opening this specific file
df= pd.read_excel("Expenses.xlsx")

#Accessing the column names
print(df.head())

# Filter years 2020 to 2025, not necessary as my data starts and stops here
df_filtered = df[df['Year'].between(2020, 2025)]

# Melt the dataframe to long format for Plotly
df_melted = df_filtered.melt(id_vars='Year', value_vars=['IN', 'OUT'],
                             var_name='Money Flow', value_name='Amount')

# Plot bar chart
fig = px.bar(
    df_melted,
    x='Year',
    y='Amount',
    color='Money Flow',
    barmode='group',
    title='Income vs Expenses (2020–2025)',
    text='Amount',
    labels={'Amount': 'Amount (in $)', 'Year': 'Year'}
)

fig.update_traces(texttemplate='%{text:.2s}', textposition='outside')
fig.update_layout(
    xaxis_title="Year",
    yaxis_title="Amount in $",
    legend_title="Money Flow",
    bargap=0.3,
    template='plotly_white'
)

fig.show()
