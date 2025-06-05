package com.jeric.ricafrente.naruto.ui.character.components

import androidx.compose.foundation.layout.aspectRatio
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Card
import androidx.compose.runtime.Composable
import androidx.compose.runtime.remember
import androidx.compose.ui.Modifier
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.unit.dp
import coil3.compose.AsyncImage
import coil3.compose.LocalPlatformContext
import coil3.request.ImageRequest
import coil3.request.crossfade
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.onboarding_3

@Composable
fun ImageCard(
    modifier: Modifier = Modifier,
    image: CharacterModel?,
) {
    val imageUrl = remember {
        if (image?.images?.isNotEmpty() == true) image.images.last() else Res.drawable.onboarding_3
    }

    val imageRequest = ImageRequest.Builder(LocalPlatformContext.current)
        .data(imageUrl)
        .crossfade(true)
        .build()
    val aspectRatio = image?.idToAspectRatio() ?: 1f

    Card(
        shape = RoundedCornerShape(10.dp),
        modifier = Modifier
            .fillMaxWidth()
            .aspectRatio(aspectRatio)
            .then(modifier)
    ) {
        AsyncImage(
            model = imageRequest,
            contentDescription = null,
            contentScale = ContentScale.Crop,
            modifier = Modifier.fillMaxSize()
        )
    }
}


fun CharacterModel.idToAspectRatio(): Float {
    val numericId = id.toIntOrNull() ?: return 1f

    val normalized = (numericId % 50) / 100f
    return (0.5f + normalized).coerceIn(0.5f, 1f)
}