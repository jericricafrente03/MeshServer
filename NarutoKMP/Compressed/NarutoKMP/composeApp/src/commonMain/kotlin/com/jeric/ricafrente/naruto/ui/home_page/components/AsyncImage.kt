package com.jeric.ricafrente.naruto.ui.home_page.components

import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.size
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.runtime.Composable
import androidx.compose.runtime.derivedStateOf
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberUpdatedState
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.unit.dp
import coil3.compose.LocalPlatformContext
import coil3.compose.SubcomposeAsyncImage
import coil3.request.ImageRequest
import coil3.request.crossfade
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.logo_squared
import org.jetbrains.compose.resources.DrawableResource
import org.jetbrains.compose.resources.painterResource


@Composable
fun AsyncImage(
    modifier: Modifier = Modifier,
    url: DrawableResource,
    contentDescription: String?,
    contentScale: ContentScale = ContentScale.Fit,
) {
    Box(modifier = modifier, contentAlignment = Alignment.Center) {

        val painter = painterResource(resource = url)

        Image(
            painter = painter,
            contentDescription = contentDescription,
            contentScale = contentScale,
            modifier = modifier
        )
    }
}