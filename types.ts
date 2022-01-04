type Material implements Node {
  id: ID!
  _id: String
  name: String!
  brand: String
  images(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialImageConnection
  reference: String
  model: String
  category: MaterialCategory!
  owner: User
  description: String
  createdAt: String!
  updatedAt: String!
}

type MaterialCategory implements Node {
  id: ID!
  _id: Int!
  name: String!
  parent: MaterialCategory
  children(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialCategoryConnection
  materials(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialConnection
}

"""Connection for MaterialCategory."""
type MaterialCategoryConnection {
  edges: [MaterialCategoryEdge]
  pageInfo: MaterialCategoryPageInfo!
  totalCount: Int!
}

"""Edge of MaterialCategory."""
type MaterialCategoryEdge {
  node: MaterialCategory
  cursor: String!
}

"""Information about the current page."""
type MaterialCategoryPageInfo {
  endCursor: String
  startCursor: String
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

"""Connection for Material."""
type MaterialConnection {
  edges: [MaterialEdge]
  pageInfo: MaterialPageInfo!
  totalCount: Int!
}

"""Edge of Material."""
type MaterialEdge {
  node: Material
  cursor: String!
}

type MaterialImage implements Node {
  id: ID!
  _id: String
  material: Material!
  imageName: String!
  imageSize: Int!
  createdAt: String!
  updatedAt: String!
}

"""Connection for MaterialImage."""
type MaterialImageConnection {
  edges: [MaterialImageEdge]
  pageInfo: MaterialImagePageInfo!
  totalCount: Int!
}

"""Edge of MaterialImage."""
type MaterialImageEdge {
  node: MaterialImage
  cursor: String!
}

"""Information about the current page."""
type MaterialImagePageInfo {
  endCursor: String
  startCursor: String
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

"""Information about the current page."""
type MaterialPageInfo {
  endCursor: String
  startCursor: String
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

type Mutation {
  """Deletes a Material."""
  deleteMaterial(input: deleteMaterialInput!): deleteMaterialPayload

  """Updates a Material."""
  updateMaterial(input: updateMaterialInput!): updateMaterialPayload

  """Creates a Material."""
  createMaterial(input: createMaterialInput!): createMaterialPayload

  """Deletes a MaterialCategory."""
  deleteMaterialCategory(input: deleteMaterialCategoryInput!): deleteMaterialCategoryPayload

  """Updates a MaterialCategory."""
  updateMaterialCategory(input: updateMaterialCategoryInput!): updateMaterialCategoryPayload

  """Creates a MaterialCategory."""
  createMaterialCategory(input: createMaterialCategoryInput!): createMaterialCategoryPayload

  """Deletes a MaterialImage."""
  deleteMaterialImage(input: deleteMaterialImageInput!): deleteMaterialImagePayload

  """Updates a MaterialImage."""
  updateMaterialImage(input: updateMaterialImageInput!): updateMaterialImagePayload

  """Creates a MaterialImage."""
  createMaterialImage(input: createMaterialImageInput!): createMaterialImagePayload

  """Deletes a User."""
  deleteUser(input: deleteUserInput!): deleteUserPayload

  """Updates a User."""
  updateUser(input: updateUserInput!): updateUserPayload

  """Creates a User."""
  createUser(input: createUserInput!): createUserPayload
}

"""A node, according to the Relay specification."""
interface Node {
  """The id of this node."""
  id: ID!
}

type Query {
  node(id: ID!): Node
  material(id: ID!): Material
  materials(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialConnection
  materialCategory(id: ID!): MaterialCategory
  materialCategories(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialCategoryConnection
  materialImage(id: ID!): MaterialImage
  materialImages(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialImageConnection
  user(id: ID!): User
  users(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): UserConnection
}

type User implements Node {
  id: ID!
  _id: String
  email: String!
  roles: Iterable!
  password: String!
  materials(
    """Returns the first n elements from the list."""
    first: Int

    """Returns the last n elements from the list."""
    last: Int

    """
    Returns the elements in the list that come before the specified cursor.
    """
    before: String

    """
    Returns the elements in the list that come after the specified cursor.
    """
    after: String
  ): MaterialConnection
  firstname: String
  lastname: String!
  createdAt: String!
  updatedAt: String!

  """A visual identifier that represents this user."""
  userIdentifier: String!
  username: String!

  """
  Returning a salt is only needed, if you are not using a modern
  hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
  """
  salt: String
}

"""Connection for User."""
type UserConnection {
  edges: [UserEdge]
  pageInfo: UserPageInfo!
  totalCount: Int!
}

"""Edge of User."""
type UserEdge {
  node: User
  cursor: String!
}

"""Information about the current page."""
type UserPageInfo {
  endCursor: String
  startCursor: String
  hasNextPage: Boolean!
  hasPreviousPage: Boolean!
}

input createMaterialCategoryInput {
  name: String!
  parent: String
  children: [String]
  materials: [String]
  clientMutationId: String
}

type createMaterialCategoryPayload {
  materialCategory: MaterialCategory
  clientMutationId: String
}

input createMaterialImageInput {
  material: String!
  imageFile: String
  imageName: String!
  imageSize: Int!
  createdAt: String!
  updatedAt: String!
  clientMutationId: String
}

type createMaterialImagePayload {
  materialImage: MaterialImage
  clientMutationId: String
}

input createMaterialInput {
  name: String!
  brand: String
  images: [String]
  reference: String
  model: String
  category: String!
  owner: String
  description: String
  createdAt: String!
  updatedAt: String!
  clientMutationId: String
}

type createMaterialPayload {
  material: Material
  clientMutationId: String
}

input createUserInput {
  email: String!
  roles: Iterable!
  password: String!
  materials: [String]
  firstname: String
  lastname: String!
  createdAt: String!
  updatedAt: String!
  clientMutationId: String
}

type createUserPayload {
  user: User
  clientMutationId: String
}

input deleteMaterialCategoryInput {
  id: ID!
  clientMutationId: String
}

type deleteMaterialCategoryPayload {
  materialCategory: MaterialCategory
  clientMutationId: String
}

input deleteMaterialImageInput {
  id: ID!
  clientMutationId: String
}

type deleteMaterialImagePayload {
  materialImage: MaterialImage
  clientMutationId: String
}

input deleteMaterialInput {
  id: ID!
  clientMutationId: String
}

type deleteMaterialPayload {
  material: Material
  clientMutationId: String
}

input deleteUserInput {
  id: ID!
  clientMutationId: String
}

type deleteUserPayload {
  user: User
  clientMutationId: String
}

input updateMaterialCategoryInput {
  id: ID!
  name: String
  parent: String
  children: [String]
  materials: [String]
  clientMutationId: String
}

type updateMaterialCategoryPayload {
  materialCategory: MaterialCategory
  clientMutationId: String
}

input updateMaterialImageInput {
  id: ID!
  material: String
  imageFile: String
  imageName: String
  imageSize: Int
  createdAt: String
  updatedAt: String
  clientMutationId: String
}

type updateMaterialImagePayload {
  materialImage: MaterialImage
  clientMutationId: String
}

input updateMaterialInput {
  id: ID!
  name: String
  brand: String
  images: [String]
  reference: String
  model: String
  category: String
  owner: String
  description: String
  createdAt: String
  updatedAt: String
  clientMutationId: String
}

type updateMaterialPayload {
  material: Material
  clientMutationId: String
}

input updateUserInput {
  id: ID!
  email: String
  roles: Iterable
  password: String
  materials: [String]
  firstname: String
  lastname: String
  createdAt: String
  updatedAt: String
  clientMutationId: String
}

type updateUserPayload {
  user: User
  clientMutationId: String
}

