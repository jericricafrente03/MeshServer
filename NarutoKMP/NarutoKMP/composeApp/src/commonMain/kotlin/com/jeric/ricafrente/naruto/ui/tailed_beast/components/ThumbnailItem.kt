package com.jeric.ricafrente.naruto.ui.tailed_beast.components

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.aspectRatio
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.derivedStateOf
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberUpdatedState
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import coil3.compose.AsyncImage
import coil3.compose.LocalPlatformContext
import coil3.compose.SubcomposeAsyncImage
import coil3.request.ImageRequest
import coil3.request.crossfade
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.onboarding_3
import org.jetbrains.compose.resources.painterResource

@Composable
fun ThumbnailItem(
    imageUrl: List<String>,
    name: String,
    jutsu: List<String>,
    natureInfo: List<String>,
    trailsInfo: List<String>,
    onClick: () -> Unit,
    modifier: Modifier = Modifier
) {
    val description = remember(name, jutsu, natureInfo, trailsInfo) {
        fun formatList(list: List<String>, fallback: String): String {
            return list
                .filter { it.isNotBlank() }
                .takeIf { it.isNotEmpty() }
                ?.joinToString(", ")
                ?: fallback
        }

        buildString {
            append("$name is a formidable entity known to utilize ")
            append(formatList(jutsu, "an unknown set of jutsu techniques"))
            append(". ")

            append("It possesses chakra nature(s) such as ")
            append(formatList(natureInfo, "an unidentified chakra nature"))
            append(", which contribute to its unique abilities. ")

            append("Among its defining characteristics, it is recognized for ")
            append(formatList(trailsInfo, "no distinctive or notable traits"))
            append(". ")

            append("These elements combine to make $name one of the most remarkable figures in its domain.")
        }
    }

    Column(
        modifier = modifier
            .width(220.dp)
            .clip(MaterialTheme.shapes.medium)
            .clickable { onClick() }
            .padding(10.dp)
    ) {
        val imageSource = remember(imageUrl) {
            imageUrl.lastOrNull().takeIf { !it.isNullOrBlank() }
                ?: Res.drawable.onboarding_3
        }

        AsyncImage(
            model = ImageRequest.Builder(LocalPlatformContext.current)
                .data(imageSource)
                .crossfade(true)
                .build(),
            placeholder = painterResource(Res.drawable.onboarding_3),
            contentDescription = null,
            contentScale = ContentScale.Crop,
            modifier = Modifier
                .fillMaxWidth()
                .aspectRatio(1.5f)
                .clip(MaterialTheme.shapes.medium)
        )

        Text(
            text = name,
            color = MaterialTheme.colorScheme.onBackground,
            style = MaterialTheme.typography.titleSmall,
        )

        Text(
            text = description,
            color = MaterialTheme.colorScheme.onBackground.copy(.8f),
            style = MaterialTheme.typography.bodyMedium,
            maxLines = 2,
            overflow = TextOverflow.Ellipsis
        )
    }
}



