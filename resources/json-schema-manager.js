"use strict";

/**
 * @param {Object} inputSchema e.g. {
 *                                    type: "object",
 *                                    required: ["last_name"],
 *                                    additionalProperties: false,
 *                                    properties: {
 *                                      first_name: {
 *                                        type: "string",
 *                                        minLength: 2
 *                                      },
 *                                      last_name: {
 *                                        type: "string",
 *                                        minLength: 2
 *                                      }
 *                                    },
 *                                    $schema: "http://json-schema.org/draft-04/schema#"
 *                                  }
 *
 * @returns {Object} e.g. {
 *                          type: "object",
 *                          additionalProperties: false,
 *                          properties: {
 *                            first_name: {
 *                              type: "string",
 *                              minLength: 2
 *                            },
 *                            last_name: {
 *                              type: "string",
 *                              minLength: 2,
 *                              required: true
 *                            }
 *                          },
 *                          $schema: "http://json-schema.org/draft-03/schema#"
 *                        }
 */
function adaptJsonSchemaToDraft03(inputSchema) {
    const schema = Object.assign({}, inputSchema);
    schema.$schema = "http://json-schema.org/draft-03/schema#";
    if (schema.required) {
        schema.required.forEach(key => schema.properties[key].required = true);
    }
    delete schema.required;
    return schema;
}

/**
 * Filter JSON schema names: parameter schemas and
 * schemas related to entities not manually editable are not considered.
 *
 * @param {String} schemaName
 *
 * @returns {boolean}
 */
function isRelevantJsonSchema(schemaName) {
    return (typeof schemaName) === "string" &&
        schemaName.indexOf("/") === -1 &&
        schemaName.indexOf("Audit") === -1;
}

/**
 * @param {Object} inputSchema e.g. {
 *                                    type: "object",
 *                                    required: ["last_name"],
 *                                    additionalProperties: false,
 *                                    properties: {
 *                                      first_name: {
 *                                        type: "string",
 *                                        minLength: 2
 *                                      },
 *                                      last_name: {
 *                                        type: "string",
 *                                        minLength: 2
 *                                      }
 *                                    },
 *                                    $schema: "http://json-schema.org/draft-04/schema#"
 *                                  }
 * @param {String} propertyName e.g. "last_name"
 *
 * @returns {Object} e.g. {
 *                          type: "object",
 *                          additionalProperties: false,
 *                          properties: {
 *                            first_name: {
 *                              type: "string",
 *                              minLength: 2
 *                            }
 *                          },
 *                          $schema: "http://json-schema.org/draft-04/schema#"
 *                        }
 */
function removeJsonSchemaProperty(inputSchema, propertyName) {
    const schema = Object.assign({}, inputSchema);
    if (schema.properties[propertyName]) {
        delete schema.properties[propertyName];
    }
    if (schema.required &&
        schema.required.indexOf(propertyName) !== -1) {
        schema.required = schema.required.filter(key => key !== propertyName);
        if (schema.required.length === 0) {
            delete schema.required;
        }
    }
    return schema;
}