package com.jeric.ricafrente.naruto.ui.home_page.components

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.offset
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.draw.scale
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import com.jeric.ricafrente.naruto.ui.home_page.utils.CategoryState
import com.jeric.ricafrente.naruto.ui.theme.Black

@Composable
fun CategoryButton(
    categoryState: CategoryState,
    onClick: () -> Unit,
    modifier: Modifier = Modifier
) {
    Row (
        verticalAlignment = Alignment.CenterVertically,
        modifier = modifier
            .clip(MaterialTheme.shapes.medium)
            .clickable{ onClick() }
            .background(
                Brush.linearGradient(
                    listOf(categoryState.startColor, categoryState.endColor)
                )
            )
            .padding(16.dp)
    ){
        Text(
            text = categoryState.title,
            color = Color.White,
            style = MaterialTheme.typography.titleMedium.copy(
                fontWeight = FontWeight.Medium
            )
        )
        Spacer(modifier = modifier.weight(1f))
        Box(
            contentAlignment = Alignment.Center
        ) {
            Box(
                modifier = Modifier
                    .scale(1f, .5f)
                    .offset(y = 40.dp)
                    .size(40.dp)
                    .background(
                        Brush.radialGradient(
                            listOf(
                                Black.copy(alpha = .3f),
                                Color.Transparent
                            ),
                        )
                    )
            )

            AsyncImage(
                url = categoryState.iconUrl,
                contentDescription = categoryState.title,
                modifier = Modifier
                    .size(40.dp),
            )
        }
    }
}
