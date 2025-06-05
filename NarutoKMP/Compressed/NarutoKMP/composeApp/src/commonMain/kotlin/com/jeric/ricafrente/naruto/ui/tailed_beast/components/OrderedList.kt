package com.jeric.ricafrente.naruto.ui.tailed_beast.components

import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.alpha
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp

@Composable
fun OrderedList(
    title: String,
    items: List<String>,
    textColor: Color
) {
    Column {
        Text(
            modifier = Modifier.padding(bottom = 10.dp),
            text = title,
            color = textColor,
            fontSize = MaterialTheme.typography.bodyMedium.fontSize,
            fontWeight = FontWeight.Bold
        )

        items
            .flatMap { it.split(" ") }  // split all items into words
            .take(4)                     // take at most 3 words total
            .forEachIndexed { index, word ->
                Text(
                    modifier = Modifier.alpha(0.5f),
                    text = "${index + 1}. $word",
                    color = textColor,
                    fontSize = MaterialTheme.typography.bodyMedium.fontSize
                )
            }
    }
}
