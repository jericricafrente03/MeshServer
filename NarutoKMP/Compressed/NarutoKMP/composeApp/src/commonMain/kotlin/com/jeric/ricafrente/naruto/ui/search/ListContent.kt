package com.jeric.ricafrente.naruto.ui.search

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.paging.LoadState
import app.cash.paging.compose.LazyPagingItems
import coil3.compose.AsyncImage
import coil3.compose.LocalPlatformContext
import coil3.request.ImageRequest
import coil3.request.crossfade
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.ui.search.components.EmptyScreen
import com.jeric.ricafrente.naruto.ui.search.components.RatingWidget
import narutokmp.composeapp.generated.resources.Res
import narutokmp.composeapp.generated.resources.image_broken
import narutokmp.composeapp.generated.resources.onboarding_3
import org.jetbrains.compose.resources.painterResource

@Composable
fun ListContent(
    padding: PaddingValues,
    heroes: LazyPagingItems<CharacterModel>,
    onClick: (String) -> Unit,
) {
    val result = shouldShowContent(character = heroes)

    if (result) {
        LazyColumn(
            modifier = Modifier
                .padding(paddingValues = padding),
            contentPadding = PaddingValues(all = 10.dp),
            verticalArrangement = Arrangement.spacedBy(10.dp)
        ) {
            items(
                items = heroes.itemSnapshotList.items,
                key = { hero ->
                    hero.id
                }
            ) { index ->
                CharacterItem(
                    id = index.id,
                    name = index.name,
                    imageUrl = index.images ?: emptyList(),
                    jutsu = index.jutsu ?: emptyList(),
                    natureInfo = index.natureType ?: emptyList(),
                    trailsInfo = index.natureType ?: emptyList(),
                    father = index.father ?: "an unknown father",
                    mother = index.mother ?: "an unknown mother",
                    brother = index.brother ?: "an unknown brother",
                    daughter = index.daughter ?: "an unknown daughter",
                    wife = index.wife ?: "an unknown wife",
                    birthDate = index.birthdate ?: "an unknown birthdate",
                    bloodType = index.bloodType ?: "an unknown bloodtype",
                    sex = index.sex ?: "an unknown sex",
                    onClick = onClick
                )
            }
        }
    }
}

@Composable
fun CharacterItem(
    id: String,
    name: String,
    imageUrl: List<String>,
    jutsu: List<String>,
    natureInfo: List<String>,
    trailsInfo: List<String>,
    father: String,
    mother: String,
    brother: String,
    daughter: String,
    wife: String,
    birthDate: String,
    bloodType: String,
    sex: String,
    onClick: (String) -> Unit,
) {
    val imageSource = remember(imageUrl) {
        imageUrl.lastOrNull().takeIf { !it.isNullOrBlank() }
            ?: Res.drawable.onboarding_3
    }

    val description = remember(
        name,
        jutsu,
        natureInfo,
        trailsInfo,
        father,
        mother,
        brother,
        daughter,
        wife,
        birthDate,
        bloodType,
        sex
    ) {
        val jutsuCount = jutsu.filter { it.isNotBlank() }.size
        val natureCount = natureInfo.filter { it.isNotBlank() }.size

        buildString {
            append("$name was born on ${birthDate.ifBlank { "an unknown date" }}. ")
            append("A ${sex.ifBlank { "Unknown" }} ninja with blood type ${bloodType.ifBlank { "Unknown" }}, ")
            append("$name is the child of ${father.ifBlank { "an unknown father" }} and ${mother.ifBlank { "an unknown mother" }}. ")

            if (brother.isNotBlank()) append("Has a sibling bond with $brother. ")
            if (daughter.isNotBlank()) append("Is also a parent to $daughter. ")
            if (wife.isNotBlank()) append("Married to $wife. ")

            append("Known for mastering $jutsuCount jutsu and wielding $natureCount chakra nature type${if (natureCount != 1) "s" else ""}. ")

            if (trailsInfo.isNotEmpty()) {
                val traits = trailsInfo.filter { it.isNotBlank() }.joinToString(", ")
                append("Notable traits include $traits. ")
            }

            append("These qualities make $name a remarkable figure in the Naruto universe.")
        }
    }

    Box(
        modifier = Modifier
            .fillMaxWidth()
            .clickable { onClick(id) }
            .height(400.dp)
            .padding(horizontal = 16.dp),
        contentAlignment = Alignment.BottomStart
    ) {
        Surface(
            shape = RoundedCornerShape(size = 20.dp),
            modifier = Modifier.fillMaxSize()
        ) {
            AsyncImage(
                model = ImageRequest.Builder(LocalPlatformContext.current)
                    .data(imageSource)
                    .crossfade(true)
                    .build(),
                placeholder = painterResource(Res.drawable.onboarding_3),
                error = painterResource(Res.drawable.image_broken),
                contentDescription = null,
                contentScale = ContentScale.Crop,
                modifier = Modifier
                    .fillMaxSize()
            )
        }
        Surface(
            modifier = Modifier
                .fillMaxHeight(0.45f)
                .fillMaxWidth(),
            color = Color.Black.copy(alpha = 0.5f),
            shape = RoundedCornerShape(
                bottomStart = 20.dp,
                bottomEnd = 20.dp
            )
        ) {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(all = 16.dp)
            ) {
                Text(
                    text = name,
                    fontSize = MaterialTheme.typography.titleLarge.fontSize,
                    fontWeight = FontWeight.Bold,
                    maxLines = 1,
                    overflow = TextOverflow.Ellipsis,
                    color = Color.White.copy(alpha = 0.5f),
                )
                Text(
                    text = description,
                    color = Color.White.copy(alpha = 0.5f),
                    fontSize = MaterialTheme.typography.bodyMedium.fontSize,
                    maxLines = 2,
                    overflow = TextOverflow.Ellipsis,
                    modifier = Modifier.padding(vertical = 6.dp)
                )
                Row(
                    modifier = Modifier.padding(top = 10.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {

                    RatingWidget(
                        modifier = Modifier.padding(end = 10.dp),
                        rating = if (name.length > 3) 5.0 else 3.0
                    )

                    Text(
                        text = "(${if (name.length > 3) 5.0 else 3.0})",
                        textAlign = TextAlign.Center,
                        color = Color.White.copy(alpha = 0.5f)
                    )
                }
            }
        }
    }
}


@Composable
fun shouldShowContent(character: LazyPagingItems<CharacterModel>): Boolean {
    val loadState = character.loadState

    val error = when {
        loadState.refresh is LoadState.Error -> loadState.refresh as LoadState.Error
        loadState.append is LoadState.Error -> loadState.append as LoadState.Error
        loadState.prepend is LoadState.Error -> loadState.prepend as LoadState.Error
        else -> null
    }

    return when {
        error != null -> {
            EmptyScreen(error = error, heroes = character)
            false
        }

        character.itemCount < 1 && loadState.refresh is LoadState.NotLoading -> {
            NoResultFound()
            false
        }

        else -> true
    }
}


@Composable
fun NoResultFound(text: String = "No results found"){
    Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
        Text(
            text = text,
            style = MaterialTheme.typography.bodyLarge,
            color = Color.Gray
        )
    }
}